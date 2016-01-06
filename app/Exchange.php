<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
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

    public function scopeUnget($query){
        $query->where('isget',0);
    }
}
