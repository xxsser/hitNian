<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attack extends Model
{
    //
    public function prize()
    {
        return $this->belongsTo('App\Prize');
    }

    public function fan()
    {
        return $this->belongsTo('App\Fan');
    }

    public function scopeHascoin($query){
        $query->where('coin','>',0);
    }
}
