<?php

namespace App\Http\Controllers;

use App\Models\Mensa;
use Carbon\Carbon;
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
    public function index($page = 0)
    {
        if(!is_numeric($page)) {
            return redirect(route('home'));
        }

        $page = intval($page);

        $mensae = Mensa::whereBetween('date', array(Carbon::today()->addWeeks($page*2), Carbon::today()->addWeeks($page*2+2)))->orderBy('date', 'ASC')->get();
        return view('home', compact('mensae', 'page'));
    }
}
