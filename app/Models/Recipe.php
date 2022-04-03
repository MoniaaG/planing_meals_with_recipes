<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravelista\Comments\Commentable;

class Recipe extends Model
{
    use HasFactory, Commentable;
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

    public function likes()
    {
        return $this->hasMany('App\Models\Like')->count();
    }

    public function liked()
    {
        return $this->hasOne('App\Models\Like')->where('user_id', Auth::id())->get();
    }

    public function opinion_average()
    {
        $opinions_users = (int)$this->hasMany('App\Models\Opinion')->sum('opinion');
        $opinion_total = $this->hasMany('App\Models\Opinion')->count() * 5;
        if($opinions_users > 0)
        return ($opinions_users/$opinion_total)*5;
        else return 0;
    }

}
