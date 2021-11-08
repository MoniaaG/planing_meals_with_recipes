<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasAnyRole('moderator', 'admin'))
            return redirect()->route('dashboard');
        else
            return redirect()->route('homepage');
    }

    public function dashboard()
    {
        return view('dashboard.welcome');
    }

    public function home()
    {
        $recipes = Recipe::where('share', 1)->get();
        //dd($recipes);
        return view('homepage', compact('recipes'));
    }
}
