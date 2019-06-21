<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
