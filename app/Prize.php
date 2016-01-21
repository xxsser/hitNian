<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    //
    public function scopeExchange($query){
        $query->where('type','<>','rank');
    }
}
