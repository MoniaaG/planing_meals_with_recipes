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
        $this->middleware('permission:dashboard', ['only' => ['dashboard']]);
        $this->middleware('permission:home', ['only' => ['home']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index() 
    {
        $recipes_newest = Recipe::where('share', 1)->orderBy('created_at', 'desc')->limit(3)->get(); 
            
        return view('homepage', compact('recipes_newest'));
    }

    public function dashboard()
    {
        return view('dashboard.welcome');
    }

    public function home()
    {
        if(Auth::user()->hasAnyRole('admin', 'moderator')) { 
            return redirect()->route('dashboard');
        }else {
            $recipes_newest = Recipe::where('share', 1)->orderBy('created_at', 'desc')->limit(3)->get(); 
            
            return view('homepage', compact('recipes_newest'));
        }
    }
}
