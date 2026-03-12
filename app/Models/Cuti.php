<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    public function jenis()
    {
        return $this->belongsTo('App\Models\JenisCuti','jenis_cuti_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
