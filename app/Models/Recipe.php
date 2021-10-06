<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    public function products() 
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('quantity');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
