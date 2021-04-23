<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    public function show(Favourite $favourite) {
        return $favourite;
    }

    public function index() {
        return Favourite::all();
    }

    public function create(Request $request) {
        $favourite = new Favourite();
        $favourite->user_id = Auth::id();
        $favourite->recipe_id = $request->recipe_id;
        $favourite->save();
    }

    public function edit(Favourite $favourite) {
        return $favourite;
    }

    public function update(Favourite $favourite, Request $request) {
        $favourite = Favourite::findOrFail($favourite->id);
        $favourite->user_id = Auth::id();
        $favourite->recipe_id = $request->recipe_id;
        $favourite->save();
    }

    public function delete(Favourite $favourite, Request $request) {
        $favourite->delete();
    }
}
