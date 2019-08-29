<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chemical extends Model
{
    protected $guarded=[];
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }
    public function laboratories()
    {
        return $this->belongsTo(Laboratory::class,'laboratory_id','id');
    }
}
