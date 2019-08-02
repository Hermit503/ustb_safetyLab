<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function equipments()
    {
        return $this->hasMany(Equipments::class,'id','laboratory_id');
    }
}
