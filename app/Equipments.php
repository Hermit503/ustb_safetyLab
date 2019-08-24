<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    protected $guarded=[];
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
