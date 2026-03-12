<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasHarian extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function pegawai()
    {
        return $this->belongsTo('App\Models\Pegawai','user_id', 'user_id');
    }
}
