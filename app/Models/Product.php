<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function unit() 
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function recipes() 
    {
        return $this->belongsToMany('App\Models\Recipe');
    }
}
