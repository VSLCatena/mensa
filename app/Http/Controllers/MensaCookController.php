<?php

namespace App\Http\Controllers;

use App\Mail\MensaCancelled;
use App\Mail\MensaPriceChanged;
use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MenuItem;
use App\Traits\LdapHelpers;
use App\Traits\Logger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MensaCookController extends Controller
{
    use LdapHelpers, Logger;

    public function __construct(){
        $this->middleware('auth');
    }

    public function showOverview(Request $request, $id){
        try {
            /* @var $mensa Mensa */
            $mensa = Mensa::findOrFail($id);
            if(Auth::user()->cant('softEdit', $mensa)){
                return redirect(route('home'))->with('error', 'Je hebt hier geen rechten voor!');
            }
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
            if(Auth::user()->cant('softEdit', $mensa)){
                return redirect(route('home'))->with('error', 'Je hebt hier geen rechten voor!');
            }
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
                $mensa->date = Carbon::today()->setTimeFromTimeString(config('mensa.default.start_time'));
                $mensa->closing_time = Carbon::today()->setTimeFromTimeString(config('mensa.default.closing_time'));
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


        $mensa->title = $request->input('title');

        if(Auth::user()->mensa_admin) {
            $mensa->date = date(new Carbon($request->input('date')));
            $mensa->closing_time = date(new Carbon($request->input('closing_time')));
            $mensa->price = $request->input('price.0.price');
            $mensa->max_users = $request->input('max_users');
            $notify = false;
            if ($mensa->price != $request->input('price.0.price')) {
                $notify = true;
            }

            // If this is a new mensa, we first check if there doesn't already exist one on this day
            if ($mensa->id == null) {
                $count = Mensa::whereBetween('date', [
                    (new Carbon($request->input('date')))->startOfDay(),
                    (new Carbon($request->input('date')))->endOfDay()
                ])->count();
                if ($count > 0) {
                    return redirect(route('home'))->with('error', 'Er bestaat al een mensa op die dag!');
                }
            }
        }

        $mensa->save(); // Save it already to retrieve the mensas ID

        // Log the editing of the mensa
        $this->log($mensa, 'Mensa gewijzigd');


        if(Auth::user()->mensa_admin) {
            // We want to remove all extra options that haven't been provided in the request
            $syncIds = array();

            $prices = $request->all('price')['price'];
            for ($i = 1; $i < count($prices); $i++) {
                if (empty($prices[$i]['description']))
                    continue;

                if (isset($prices[$i]['id'])) {
                    $mensaPrice = MensaExtraOption::find($prices[$i]['id']);
                } else {
                    $mensaPrice = new MensaExtraOption();
                    if($mensa->id !== $mensaPrice->mensa->id){
                        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('error', 'Whoops! Er ging iets fout.');
                    }
                }

                if ($mensaPrice->price != $prices[$i]['price']) {
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
            if ($notify) {
                foreach ($mensa->users as $user) {
                    if ($user->user->email == null)
                        continue;

                    Mail::to($user->user)->send(new MensaPriceChanged($user));
                }
            }
        }

        // Here we do menu stuff
        // We want to remove all menu items that haven't been provided in the request
        $syncIds = array();

        $menu = $request->all('menu')['menu'];
        if($menu != null) {
            for ($i = 0; $i < count($menu); $i++) {
                if (empty($menu[$i]['text']))
                    continue;

                if (isset($menu[$i]['id'])) {
                    $menuItem = MenuItem::find($menu[$i]['id']);
                    if ($mensa->id != $menuItem->mensa->id) {
                        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('error', 'Whoops! Er ging iets fout.');
                    }
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
        }

        // Delete all menu options that aren't included anymore
        $mensa->menuItems()->whereNotIn('id', $syncIds)->delete();


        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa aangemaakt/gewijzigd!');
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
}
