<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudyContent extends Model
{
    public function Unit(){
        return $this->belongsToMany('App\Unit','content_record','content_id','unit_id');
    }
}
