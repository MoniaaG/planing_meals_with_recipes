<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $hidden = ['pivot'];
    public function products() 
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('quantity');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function recipes()
    {
        return $this->belongsToMany('App\Models\Calendar')->withPivot('start_at', 'end_at', 'text_color', 'background_color');
    }
}
