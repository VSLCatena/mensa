<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MensaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    public function edit(Request $request, $id = 0){
        try {
            $mensa = Mensa::findOrFail($id);
        } catch(ModelNotFoundException $e){
            $mensa = new Mensa();
        }

        if($request->isMethod('get')){
            return view('mensae.edit', compact('mensa'));
        }

        $request->validate([
            'title' => 'required|max:255',
            'date' => 'required|date|after_or_equal:today',
            'closing_time' => 'required|date|before:date',
            'max_users' => 'required|numeric|between:1,999'
        ]);

        $mensa->title = htmlspecialchars($request->input('title'));
        $mensa->date = date("Y-m-d H:i:s", strtotime($request->input('date')));
        $mensa->closing_time = date("Y-m-d H:i:s", strtotime($request->input('closing_time')));
        $mensa->max_users = htmlspecialchars($request->input('max_users'));
        $mensa->save();

        return redirect(route('home'));
    }
}
