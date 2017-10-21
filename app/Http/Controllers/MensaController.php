<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use App\Models\MensaExtraOption;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MensaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    public function edit(Request $request){
        try {
            if($request->has('id')) {
                $mensa = Mensa::findOrFail($request->get('id'));
            } else {
                $mensa = new Mensa();
                $mensa->title = 'Mensa met betaalde afwas';
                $mensa->max_users = 42;
            }
        } catch(ModelNotFoundException $e){
            return redirect(route('home'), ['error' => 'Mensa niet gevonden.']);
        }

        if(!$request->has('edited')){
            return view('mensae.edit', compact('mensa'));
        }

        $request->validate([
            'title' => 'required|max:255',
            'date' => 'required|date|after_or_equal:today',
            'closing_time' => 'required|date|before:date',
            'max_users' => 'required|numeric|between:0,999',
            'price.*.description' => 'max:255',
            'price.*.price' => 'numeric|between:0,99',
            'price.*.id' => 'exists:mensa_extra_options',
        ]);

        $mensa->title = $request->input('title');
        $mensa->date = date("Y-m-d H:i:s", strtotime($request->input('date')));
        $mensa->closing_time = date("Y-m-d H:i:s", strtotime($request->input('closing_time')));
        $mensa->max_users = $request->input('max_users');
        $mensa->price = $request->input('price.0.price');

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

        $mensa->save();

        return redirect(route('home'));
    }
}
