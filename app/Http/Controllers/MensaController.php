<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use App\Models\MensaExtraOption;
use App\Models\MensaUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MensaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    public function showOverview(Request $request){
        try {
            $mensa = Mensa::findOrFail($request->get('id'));
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $users = $mensa->users->count();
        $intros = $mensa->users->where('is_intro', '1')->count();
        $cooks = $mensa->users->where('cooks', '1')->count();
        $dishwashers = $mensa->users->where('dishwasher', '1')->count();
        $budget = $mensa->budget();

        return view('mensae.overview', compact('mensa', 'users', 'intros', 'cooks', 'dishwashers', 'budget'));
    }

    public function showSignins(Request $request){
        try {
            $mensa = Mensa::findOrFail($request->get('id'));
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        $users = $mensa->users()->join('users', 'users.lidnummer', '=', 'mensa_users.lidnummer')
            ->orderBy('mensa_users.cooks', 'DESC')
            ->orderBy('mensa_users.dishwasher', 'DESC')
            ->orderBy('users.name')->get();

        return view('mensae.signins', compact('mensa', 'users'));
    }

    public function edit(Request $request){
        try {
            if($request->has('id') && !empty($request->get('id'))) {
                $mensa = Mensa::findOrFail($request->get('id'));
            } else {
                $mensa = new Mensa();
                $mensa->title = env('MENSA_DEFAULT_NAME', '');
                $mensa->max_users = env('MENSA_DEFAULT_MAX_USERS', 50);
                $mensa->price = env('MENSA_DEFAULT_PRICE', 3.5);
            }
        } catch(ModelNotFoundException $e){
            return redirect(route('home'))->with('error', 'Mensa niet gevonden.');
        }

        if(!$request->has('edited')){
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


        return redirect(route('home'))->with('info', 'Mensa aangemaakt/gewijzigd!');
    }

    public function togglePaid(Request $request){
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
}
