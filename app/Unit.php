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

    public function chemicals()
    {
        return $this->hasMany(Chemical::class,'unit_id','id');
    }
    public function inspection_logs()
    {
        return $this->hasMany(InspectionLog::class,'unit_id','id');
    }

    public function examManager(){
        return $this->hasOne(examManage::class,'unit_id','id');
    }
}
