<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mensae = Mensa::where('date', '>', date("Y-m-d"))->orderBy('date', 'ASC')->get();
        return view('home', compact('mensae'));
    }
}
