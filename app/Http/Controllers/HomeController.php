<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NowPlaying;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard() {
        $nowplayings = NowPlaying::all();

        return view('manager.home', [
            'nowplayings' => $nowplayings,
        ]);
    }
}
