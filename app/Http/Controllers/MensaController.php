<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use App\Traits\LdapHelpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MensaController extends Controller
{
    use LdapHelpers;

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


        $staffIds = $mensa->staff()->map(function($item){
            return $item->id;
        });

        return view('mensae.overview', compact('mensa', 'users', 'staffIds', 'vegetarians', 'intros', 'cooks', 'dishwashers', 'budget', 'payingUsers'));
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
                $mensa->title = env('MENSA_DEFAULT_NAME', '');
                $mensa->max_users = env('MENSA_DEFAULT_MAX_USERS', 50);
                $mensa->price = env('MENSA_DEFAULT_PRICE', 3.5);
            }
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        if($request->isMethod('get')){
            return view('mensae.editmensa', compact('mensa'));
        }

        $request->validate([
            'title' => 'required|max:191',
            'date' => 'required|date|after_or_equal:today',
            'closing_time' => 'required|date|before:date',
            'max_users' => 'required|numeric|between:0,999',
            'price.0.price' => 'required|numeric|between:0,99',
            'price.*.description' => 'max:191',
            'price.*.price' => 'numeric|between:0,99',
            'price.*.id' => 'exists:mensa_extra_options',
        ]);

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

        // We want to remove all extra options that haven't been provided in the request
        $syncIds = array();

        $prices = $request->all('price')['price'];
        for($i = 1; $i < count($prices); $i++){
            if(isset($prices[$i]['id'])) {
                $mensaPrice = MensaExtraOption::find($prices[$i]['id']);
            } else {
                $mensaPrice = new MensaExtraOption();
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


        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa aangemaakt/gewijzigd!');
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

        $mensaUser->paid = ($mensaUser->paid == $mensaUser->price()) ? 0 : $mensaUser->price();
        $mensaUser->save();

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

        if($request->isMethod('get')){
            return view('mensae.confirmsignout', compact('mUser'));
        }

        $mUser->delete();

        return redirect(route('mensa.signins', ['id' => $mensaId]))->with('info', $mUser->user->name.' is uitgeschreven!');
    }

    // This is for if you're an admin
    public function newSignin(Request $request, $mensaId){
        try {
            $mensa = Mensa::findOrFail($mensaId);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden!');
        }

        if($request->isMethod('get')){
            return view('mensae.newsignin', compact('mensa'));
        }

        $request->validate([
            'lidnummer' => 'required',
        ]);

        return redirect(route('signin', ['id' => $mensaId]))->with('asAdmin', 'true')->with('extra_lidnummer', $request->get('lidnummer'));
    }

    public function requestUserLookup(Request $request){
        $request->validate(['name' => 'regex:/^[a-zA-Z _]+$/']);
        return response()->json($this->searchLdapUsers($request->get('name')));
    }
}
