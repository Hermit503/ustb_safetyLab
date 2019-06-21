<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function laboratories()
    {
        return $this->hasMany(Laboratory::class,'id','unit_id');
    }
}
