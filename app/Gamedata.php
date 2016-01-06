<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gamedata extends Model
{
    //
    public function fan()
    {
        return $this->hasOne('App\Fan','id','fan_id');
    }
}
