<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamManage extends Model
{
    protected $guarded=[];
    public function units()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
