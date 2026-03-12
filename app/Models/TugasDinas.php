<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasDinas extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function surat()
    {
        return $this->belongsTo('App\Models\SuratInternal','surat_internal_id');
    }
}
