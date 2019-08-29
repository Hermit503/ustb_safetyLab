<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    protected $guarded=[];
    public function laboratories()
    {
        return $this->belongsTo(Laboratory::class,'laboratory_id','id');
    }
}
