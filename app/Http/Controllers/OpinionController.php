<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpinionController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        $opinion = new Opinion();
        $opinion->creator_id = Auth::id();
        $opinion->recipe_id = $recipe->id;
        $opinion->opinion = $request->opinion;
        $opinion->save();
        return redirect()->route(\Request::route()->getName());
    }
}
