<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hidden extends Model
{
    protected $guarded=[];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function hiddens_logs()
    {
        return $this->hasMany(HiddensLog::class,'hidden_id','id');
    }


}

