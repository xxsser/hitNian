<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fan extends Model
{
    //
    protected $fillable = ['nikename','openid','sex'];

    public function attacks()
    {
        return $this->hasMany('App\Attack');
    }

}
