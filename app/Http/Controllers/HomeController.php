<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
        if(Auth::check() && Auth::user()->hasAnyRole('admin', 'moderator')){
            return redirect()->route('dashboard');
        }
        $recipes_newest = Recipe::where('share', 1)->orderBy('created_at', 'desc')->limit(3)->get();
        $most_liked = Like::select('recipe_id', DB::raw('count(recipe_id) as counts'))->groupBy('recipe_id')->orderBy('counts', 'DESC')->limit(6)->pluck('recipe_id');
        $recipes_most_liked = [];
        foreach($most_liked as $liked){
            $recipes_most_liked[] = (Recipe::where('id', $liked)->first());
        }
        $recipes_most_liked = collect($recipes_most_liked); 
        return view('homepage', compact('recipes_newest', 'recipes_most_liked'));
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

    public function notifications()
    {
        return view('notification');
    }

    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }

    public function markNotificationAll(Request $request)
    {
        auth()->user()->unreadNotifications()->get()->markAsRead();
        return response()->noContent();
    }
}
