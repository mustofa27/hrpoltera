<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'email', 'password', 'username', 'image_path', 'api_token', 'fcm_id', 'tipe_user_id', 'unit_kerja_id', 'jenis_kelamin', 'alamat', 'agama', 'tempat_lahir', 'tanggal_lahir', 'no_hp',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sso_synced_at' => 'datetime',
    ];

    public function bawahan()
    {
        return $this->hasMany('App\Models\Pegawai','atasan_langsung_id');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\UnitKerja','unit_kerja_id');
    }

    public function cuti()
    {
        return $this->hasMany('App\Models\Cuti','user_id');
    }

    public function validasiLupa()
    {
        return $this->hasMany('App\Models\LupaAbsen','atasan_id');
    }

    public function role()
    {
        return $this->hasOne('App\Models\Role');
    }
}
