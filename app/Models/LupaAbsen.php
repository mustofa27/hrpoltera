<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LupaAbsen extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function atasan()
    {
        return $this->belongsTo('App\Models\User','atasan_id');
    }
}
