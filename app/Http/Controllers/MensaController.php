<?php

namespace App\Http\Controllers;

use App\Mail\MensaCancelled;
use App\Mail\MensaPriceChanged;
use App\Mail\MensaState;
use App\Mail\SigninCancelled;
use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use App\Models\MenuItem;
use App\Traits\LdapHelpers;
use App\Traits\Logger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MensaController extends Controller
{
    use LdapHelpers, Logger;

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    public function showOverview(Request $request, $id){
        try {
            /* @var $mensa Mensa */
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $users = $mensa->users()->count();
        $intros = $mensa->users()->where('is_intro', '1')->count();
        $cooks = count($mensa->cooks());
        $dishwashers = count($mensa->dishwashers());
        $vegetarians = $mensa->users()->where('vegetarian', '1')->count();
        $budget = $mensa->budget();
        $payingUsers = $mensa->payingUsers();


        $unconfirmedUsers = $mensa->users()->onlyTrashed()->distinct()
            ->where('confirmed', '0')
            ->whereNotIn('lidnummer', $mensa->users()->get(['lidnummer'])->map(function($el){ return $el->lidnummer; }))
            ->get(['lidnummer', 'mensa_id']);


        $staffIds = $mensa->staff()->map(function($item){
            return $item->id;
        });

        return view('mensae.overview', compact('mensa', 'users', 'unconfirmedUsers', 'staffIds', 'vegetarians', 'intros', 'cooks', 'dishwashers', 'budget', 'payingUsers'));
    }

    public function showSignins(Request $request, $id){
        try {
            /* @var $mensa Mensa */
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $users = $mensa->users(true)->get();

        return view('mensae.signins', compact('mensa', 'users'));
    }

    public function edit(Request $request, $id = null){
        try {
            if($id != null){
                /* @var $mensa Mensa */
                $mensa = Mensa::findOrFail($id);
            } else {
                $mensa = new Mensa();
                $mensa->title = config('mensa.default.name');
                $mensa->max_users = config('mensa.default.max_users');
                $mensa->price = config('mensa.default.price');
            }
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        if($mensa->closed){
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('error', 'Deze mensa is al gesloten!');
        }

        if($request->isMethod('get')){
            return view('mensae.editmensa', compact('mensa'));
        }

        $request->validate([
            'title' => 'required|max:191',
            'date' => 'required|date',
            'closing_time' => 'required|date|before:date',
            'max_users' => 'required|numeric|between:0,999',
            'price.0.price' => 'required|numeric|between:0,99',
            'price.*.description' => 'max:191',
            'price.*.price' => 'numeric|between:0,99',
            'price.*.id' => 'exists:mensa_extra_options',
            'menu.*.id' => 'exists:menu_items',
            'menu.*.order' => 'numeric',
            'menu.*.text' => 'max:191'
        ]);

        $notify = false;

        if($mensa->price != $request->input('price.0.price')){
            $notify = true;
        }


        $mensa->title = $request->input('title');
        $mensa->date = date(new Carbon($request->input('date')));
        $mensa->closing_time = date(new Carbon($request->input('closing_time')));
        $mensa->max_users = $request->input('max_users');
        $mensa->price = $request->input('price.0.price');

        // If this is a new mensa, we first check if there doesn't already exist one on this day
        if($mensa->id == null){
            $count = Mensa::whereBetween('date', [
                (new Carbon($request->input('date')))->startOfDay(),
                (new Carbon($request->input('date')))->endOfDay()
            ])->count();
            if($count > 0){
                return redirect(route('home'))->with('error', 'Er bestaat al een mensa op die dag!');
            }
        }

        $mensa->save(); // Save it already to retrieve the mensas ID

        // Log the editing of the mensa
        $this->log($mensa, 'Mensa gewijzigd');

        // We want to remove all extra options that haven't been provided in the request
        $syncIds = array();

        $prices = $request->all('price')['price'];
        for($i = 1; $i < count($prices); $i++){
            if(empty($prices[$i]['description']))
                continue;

            if(isset($prices[$i]['id'])) {
                $mensaPrice = MensaExtraOption::find($prices[$i]['id']);
            } else {
                $mensaPrice = new MensaExtraOption();
            }

            if($mensaPrice->price != $prices[$i]['price']){
                $notify = true;
            }

            $mensaPrice->description = $prices[$i]['description'];
            $mensaPrice->price = $prices[$i]['price'];
            $mensaPrice->mensa()->associate($mensa);

            $mensaPrice->save();

            // Add the mensaPrice to the syncIds so it won't be deleted!
            $syncIds[] = $mensaPrice->id;
        }

        // Delete all extra options that aren't included anymore
        $mensa->extraOptions()->whereNotIn('id', $syncIds)->delete();

        // If the price has changed we want to notify everyone that signed in
        if($notify){
            foreach($mensa->users as $user){
                if($user->user->email == null)
                    continue;

                Mail::to($user->user)->send(new MensaPriceChanged($user));
            }
        }

        // Here we do menu stuff
        // We want to remove all menu items that haven't been provided in the request
        $syncIds = array();

        $menu = $request->all('menu')['menu'];
        for($i = 0; $i < count($menu); $i++){
            if(empty($menu[$i]['text']))
                continue;

            if(isset($menu[$i]['id'])) {
                $menuItem = MenuItem::find($menu[$i]['id']);
            } else {
                $menuItem = new MenuItem();
            }

            $menuItem->text = $menu[$i]['text'];
            $menuItem->order = $menu[$i]['order'];
            $menuItem->mensa()->associate($mensa);

            $menuItem->save();

            // Add the mensaPrice to the syncIds so it won't be deleted!
            $syncIds[] = $menuItem->id;
        }

        // Delete all menu options that aren't included anymore
        $mensa->menuItems()->whereNotIn('id', $syncIds)->delete();


        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa aangemaakt/gewijzigd!');
    }

    public function showLogs(Request $request, $mensaId){
        try {
            /* @var $mensa Mensa */
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $logs = $mensa->logs()->orderBy('created_at', 'DESC')->get();
        return view('mensae.logs', compact('mensa', 'logs'));
    }

    public function togglePaid(Request $request, $mensaId){
        try {
            /* @var $mensaUser MensaUser */
            $mensaUser = MensaUser::findOrFail($request->get('id'));
        } catch(ModelNotFoundException $e){
            return response()->json([
                'error' => 'MensaUser niet gevonden!'
            ]);
        }

        if($mensaUser->mensa->closed){
            return response()->json(['error' => 'Deze mensa is al gesloten!']);
        }

        $mensaUser->paid = ($mensaUser->paid == $mensaUser->price()) ? 0 : $mensaUser->price();
        $mensaUser->save();

        // Log the payment change
        $this->log($mensaUser->mensa, ($mensaUser->is_intro?'Intro van ':'').$mensaUser->user->name.($mensaUser->paid?' heeft betaald.':' is op niet betaald gezet.'));

        return response()->json([
            'price' => '&euro;'.number_format($mensaUser->price(), 2),
            'paid' => $mensaUser->paid == $mensaUser->price()
        ]);
    }

    public function removeSignin(Request $request, $mensaId, $userId){
        try {
            $mUser = MensaUser::findOrFail($userId);
        } catch(ModelNotFoundException $e){
            return redirect(route('mensa.signins', ['id' => $mensaId]))->with('error', 'Gebruiker niet gevonden!');
        }

        if($mUser->mensa->closed){
            return redirect(route('mensa.overview', ['id' => $mensaId]))->with('error', 'Deze mensa is al gesloten!');
        }

        if($request->isMethod('get')){
            return view('mensae.confirmsignout', compact('mUser'));
        }

        $mUser->delete();

        // Log the deletion
        $this->log($mUser->mensa, $mUser->user->name.' is uitgeschreven.');

        // Send signout email
        Mail::to($mUser->user)->send(new SigninCancelled($mUser));

        return redirect(route('mensa.signins', ['id' => $mensaId]))->with('info', $mUser->user->name.' is uitgeschreven!');
    }

    // This is for if you're an admin
    public function newSignin(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($mensa->closed){
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('error', 'Deze mensa is al gesloten!');
        }

        if($request->isMethod('get')){
            return view('mensae.newsignin', compact('mensa'));
        }

        $request->validate([
            'lidnummer' => 'required',
        ]);

        if($request->has('bulk')){
            return redirect(route('mensa.newsignin.bulk', ['id' => $mensa->id, 'lidnummer' => $request->get('lidnummer')]));
        }

        return redirect(route('signin', ['id' => $mensaId, 'lidnummer' => $request->get('lidnummer')]));
    }

    public function requestUserLookup(Request $request){
        $request->validate(['name' => 'regex:/^[a-zA-Z _]+$/']);
        return response()->json($this->searchLdapUsers($request->get('name')));
    }

    public function printState(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($request->isMethod("get")){
            return view('mensae.confirmprintstate', compact('mensa'));
        }
        Mail::to(config('mensa.contact.printer'))->send(new MensaState($mensa));

        $mensa->closed = true;
        $mensa->save();

        // Log the printing of the state
        $this->log($mensa, 'Het wijzigen van de mensa is geblokkeerd i.v.m. het printen van de mensastaat');
        $this->log($mensa, 'Mensastaat uitgeprint.');

        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensastaat is uitgeprint!');
    }

    public function printStatePreview(Request $request, $mensaId){

        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        // Delete all unconfirmed users
        $mensa->users()->where('confirmed', '0')->delete();

        return new MensaState($mensa);
    }

    public function openMensa(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($request->isMethod('get')){
            return view('mensae.confirmreopen', compact('mensa'));
        }

        if(!$mensa->closed) {
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Deze mensa is al gesloten!');
        }

        $mensa->closed = false;
        $mensa->save();

        $this->log($mensa, 'Mensa opnieuw geopend voor wijzigingen');
        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa geopend voor aanpassingen!');
    }

    public function closeMensa(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($mensa->closed) {
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Deze mensa is al geopend!');
        }

        $mensa->closed = true;
        $mensa->save();

        $this->log($mensa, 'Mensa gesloten voor wijzigingen');
        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa gesloten voor aanpassingen!');
    }

    public function cancelMensa(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($mensa->max_users <= 0) {
            return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Deze mensa is al geannuleerd!');
        }

        if($request->isMethod("get")){
            return view('mensae.confirmcancel', compact('mensa'));
        }

        $mensa->max_users = 0;
        $mensa->save();

        foreach($mensa->users as $user){
            if($user->user->email == null)
                continue;

            Mail::to($user->user)->send(new MensaCancelled($user));
        }

        $this->log($mensa, 'Mensa geannuleerd');
        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa geannuleerd!');
    }

    public function bulkSignin(Request $request, $mensaId, $lidnummer){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        $user = $this->getLdapUserBy('description', $lidnummer);
        if($user == null){
            return redirect()->back()->with('error', 'Inschrijving niet gevonden! Als je denkt dat dit een fout is neem dan contact op met ' . config('mensa.contact.mail') . '.');
        }

        if($request->isMethod('get')){
            return view('mensae.bulkenlistintros', compact('mensa', 'user'));
        }

        $baseIntroUser = new MensaUser();
        $baseIntroUser->is_intro = true;
        $baseIntroUser->cooks = false;
        $baseIntroUser->dishwasher = false;
        $baseIntroUser->paid = false;
        $baseIntroUser->confirmed = true;
        $baseIntroUser->mensa()->associate($mensa);
        $baseIntroUser->lidnummer = $user->lidnummer;
        $baseIntroUser->confirmation_code = bin2hex(random_bytes(32));

        foreach($request->all("intro")['intro'] as $introData){
            $introUser = $baseIntroUser->replicate();
            $introUser->vegetarian = isset($introData['vegetarian']);
            $introUser->allergies = $introData['allergies'];
            $introUser->extra_info = $introData['info'];

            $introUser->save();
            if(isset($introData['extraOptions'])){
                foreach($introData['extraOptions'] as $id){
                    try {
                        $extraOption = $mensa->extraOptions()->findOrFail($id);
                        $introUser->extraOptions()->attach($extraOption);
                    } catch(ModelNotFoundException $e){}
                }
            }
        }

        $count = count($request->all('intro')['intro']);

        $this->log($mensa, $count.' intro'.($count == 1?'':'s').' ingeschreven voor '.$user->name.'.');

        return redirect(route('mensa.signins', ['id' => $mensa->id]))->with('info', $count.' intro'.($count == 1?'':'s').' succesvol ingeschreven voor '.$user->name.'!');
    }
}
