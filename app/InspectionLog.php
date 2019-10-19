<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionLog extends Model
{

    protected $guarded = [];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
