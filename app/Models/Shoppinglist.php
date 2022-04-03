<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoppinglist extends Model
{
    use HasFactory;

    //protected $table = 'shoppinglists';
    protected $fillable = ['owner_id', 'start_date', 'end_date'];

    protected $dates = ['start_date', 'end_date'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('quantity', 'added_to_pantry');
    }
}
