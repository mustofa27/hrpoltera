<?php

namespace App\Providers;

use App\Models\Cuti;
use App\Models\LupaAbsen;
use App\Models\Pegawai;
use App\Models\TugasDinas;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('approve-lupa-absen', function (User $user, LupaAbsen $lupaAbsen): bool {
            return (int) $lupaAbsen->atasan_id === (int) $user->id && $lupaAbsen->status === 'requested';
        });

        Gate::define('reject-lupa-absen', function (User $user, LupaAbsen $lupaAbsen): bool {
            return (int) $lupaAbsen->atasan_id === (int) $user->id && $lupaAbsen->status === 'requested';
        });

        Gate::define('approve-cuti-atasan', function (User $user, Cuti $cuti): bool {
            if ($cuti->pertimbangan_atasan !== 'requested') {
                return false;
            }

            return Pegawai::query()
                ->where('user_id', $cuti->user_id)
                ->where('atasan_langsung_id', $user->id)
                ->exists();
        });

        Gate::define('approve-cuti-pejabat', function (User $user, Cuti $cuti): bool {
            if ($cuti->pertimbangan_atasan === 'requested') {
                return false;
            }

            if ($user->username === 'adminpusat') {
                return true;
            }

            return Pegawai::query()
                ->where('user_id', $cuti->user_id)
                ->where('atasan_langsung_id', $user->id)
                ->exists();
        });

        Gate::define('delete-tugas-dinas', function (User $user, TugasDinas $tugasDinas): bool {
            return (int) $tugasDinas->user_id === (int) $user->id || $user->username === 'adminpusat';
        });
    }
}
