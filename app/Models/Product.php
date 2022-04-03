<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $hidden = ['pivot'];

    public function unit() 
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function recipes() 
    {
        return $this->belongsToMany('App\Models\Recipe');
    }

    public function pantries() 
    {
        return $this->belongsToMany('App\Models\Pantry');
    }

    public function shoppinglists() 
    {
        return $this->belongsToMany('App\Models\Shoppinglist');
    }

    public function product_category() 
    {
        return $this->belongsTo('App\Models\ProductCategory');
    }
}
