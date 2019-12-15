<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hidden extends Model
{
    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','user_id');
    }
    public function hiddens_logs()
    {
        return $this->hasMany(HiddensLog::class,'hidden_id','id');
    }


}

