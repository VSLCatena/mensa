<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MensaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    public function showOverview(Request $request, $id){
        try {
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $users = $mensa->users()->count();
        $intros = $mensa->users()->where('is_intro', '1')->count();
        $cooks = $mensa->users()->where('cooks', '1')->count();
        $dishwashers = $mensa->users()->where('dishwasher', '1')->count();
        $budget = $mensa->budget();
        $payingUsers = $mensa->users()->where('cooks', '0')->where('dishwasher', '0')->count();

        return view('mensae.overview', compact('mensa', 'users', 'intros', 'cooks', 'dishwashers', 'budget', 'payingUsers'));
    }

    public function showSignins(Request $request, $id){
        try {
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $users = $mensa->users()->select(DB::raw('*, mensa_users.wishes as uwishes, mensa_users.allergies as uallergies'))->join('users', 'users.lidnummer', '=', 'mensa_users.lidnummer')
            ->orderBy('mensa_users.cooks', 'DESC')
            ->orderBy('mensa_users.dishwasher', 'DESC')
            ->orderBy('users.name')->get();

        return view('mensae.signins', compact('mensa', 'users'));
    }

    public function edit(Request $request, $id = null){
        try {
            if($id != null){
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
            return view('mensae.edit', compact('mensa'));
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
        $mensa->date = date("Y-m-d H:i:s", strtotime($request->input('date')));
        $mensa->closing_time = date("Y-m-d H:i:s", strtotime($request->input('closing_time')));
        $mensa->max_users = $request->input('max_users');
        $mensa->price = $request->input('price.0.price');
        $mensa->save(); // Save it already to retrieve the mensas ID

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
        }


        return redirect(route('mensa.overview', ['id' => $mensa->id]))->with('info', 'Mensa aangemaakt/gewijzigd!');
    }

    public function togglePaid(Request $request, $mensaId){
        try {
            $mensaUser = MensaUser::findOrFail($request->get('id'));
        } catch(ModelNotFoundException $e){
            return response()->json([
                'error' => 'MensaUser niet gevonden!'
            ]);
        }

        $mensaUser->paid = !$mensaUser->paid;
        $mensaUser->save();

        return response()->json([
            'paid' => $mensaUser->paid
        ]);
    }

    public function editSignin(Request $request, $mensaId, $userId){
        try {
            $mensaUser = MensaUser::findOrFail($userId);
        } catch(ModelNotFoundException $e){
            return redirect(route('mensa.signins', ['id' => $mensaId]))->with('error', 'Gebruiker niet gevonden!');
        }

        if($request->isMethod('get')){
            return view('mensae.editsignin', compact('mensaUser'));
        }


        // We validate the request
        $request->validate([
            'allergies' => 'max:191',
            'wishes' => 'max:191',
            'extra.*' => 'exists:mensa_extra_options,id',
        ]);

        $mensaUser->cooks = (bool)$request->has('cooks');
        $mensaUser->dishwasher = (bool)$request->has('dishwasher');
        $mensaUser->allergies = $request->input('allergies');
        $mensaUser->wishes = $request->input('wishes');

        // And lastly we save the user
        $mensaUser->save();
        foreach($request->all('extra') as $id){
            try {
                $extraOption = $mensaUser->mensa->extraOptions()->findOrFail($id);
                $mensaUser->extraOptions()->attach($extraOption);
            } catch(ModelNotFoundException $e){}
        }

        return redirect(route('mensa.signins', ['id' => $mensaId]))->with('info', 'De gebruiker '.$mensaUser->user->name.' is aangepast!');

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
}
