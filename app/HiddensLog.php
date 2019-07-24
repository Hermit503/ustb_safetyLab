<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HiddensLog extends Model
{
    public function hiddens()
    {
        return $this->belongsTo(Hidden::class);
    }
}
