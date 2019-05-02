<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pantalla;

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
        $pantallas = Pantalla::all();

        return view('manager.home', [
            'pantallas' => $pantallas,
        ]);
    }
}
