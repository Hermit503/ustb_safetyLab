<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded=[];
    public function laboratories()
    {
        return $this->hasMany(Laboratory::class,'id','unit_id');
    }
    public function users()
    {
        return $this->hasMany(User::class,'unit_id','id');
    }
}
