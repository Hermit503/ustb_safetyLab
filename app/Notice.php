<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $guarded=[];

    public function user()
    {
        return $this->belongsTo(User::class,'build_id','user_id');
    }
}
