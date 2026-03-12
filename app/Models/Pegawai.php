<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;

class Pegawai extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function atasan()
    {
        return $this->belongsTo('App\Models\User','atasan_langsung_id');
    }

    public function shift()
    {
        return $this->belongsTo('App\Models\Shift', 'shift_id');
    }

    public function absen()
    {
        return $this->hasMany('App\Models\Absensi', 'user_id', 'user_id');
    }

    public function getAbsensiAttribute()
    {
        $date = new DateTime("now", new DateTimeZone('Asia/Jakarta') );
        $tanggal = $date->format('Y-m-d');
        return $this->absen->where('tanggal', $tanggal)->first();
    }
}
