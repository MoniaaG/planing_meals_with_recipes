<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Calendar extends Model
{
    use HasFactory;

    public $timestamps = TRUE;

    protected $fillable = [
        'owner_id',
    ];


    public function recipes()
    {
        return $this->belongsToMany('App\Models\Recipe')->withPivot('id', 'start_at', 'end_at', 'text_color', 'background_color','cooked');
    }

    public function recipes_events()
    {
        return $this->recipes()->select('calendar_recipe.id','name as title','start_at as start', 'end_at as end', 'text_color as textColor', 'background_color as color', 'cooked', 'recipe_id')->get()->toArray();
    }

    public function recipes_events_day(String $date)
    {
        return $this->recipes()->select('calendar_recipe.id','name as title','start_at as start', 'end_at as end', 'text_color as textColor', 'background_color as color', 'cooked', 'recipe_id')->where('start_at',$date)->get()->toArray();
    }
}
