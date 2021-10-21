<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pantry extends Model
{
    use HasFactory;

    public $timestamps = TRUE;

    protected $fillable = [
        'owner_id',
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')->withPivot('quantity', 'expiration_date');
    }
}
