<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Shift;
use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index(): View
    {
        $pegawai = Pegawai::query()->with(['user', 'atasan', 'shift'])->get();
        $users = User::query()->orderBy('nama')->get();
        $units = UnitKerja::query()->orderBy('nama')->get();
        $shifts = Shift::query()->orderBy('nama')->get();

        return view('pegawai.index', compact('pegawai', 'users', 'units', 'shifts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'nip' => ['required', 'string', 'max:50'],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'atasan_langsung_id' => ['required', 'integer', 'exists:users,id'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:100'],
            'urutan' => ['required', 'integer'],
        ]);

        $pegawai = new Pegawai();
        $pegawai->nip = $validated['nip'];
        $pegawai->nik = '-';
        $pegawai->pangkat_id = 1;
        $pegawai->golongan_id = 1;
        $pegawai->user_id = (int) $validated['user_id'];
        $pegawai->gelar_depan = $validated['gelar_depan'] ?? null;
        $pegawai->gelar_belakang = $validated['gelar_belakang'] ?? null;
        $pegawai->status_nikah = '-';
        $pegawai->shift_id = (int) $validated['shift_id'];
        $pegawai->npwp = '-';
        $pegawai->urutan = (int) $validated['urutan'];
        $pegawai->is_serdos = 0;
        $pegawai->is_remun = 0;
        $pegawai->kampus_id = 1;
        $pegawai->atasan_langsung_id = (int) $validated['atasan_langsung_id'];
        $pegawai->save();

        return redirect('/pegawais');
    }

    public function update(Request $request, Pegawai $pegawai): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'nip' => ['required', 'string', 'max:50'],
            'shift_id' => ['required', 'integer', 'exists:shifts,id'],
            'atasan_langsung_id' => ['required', 'integer', 'exists:users,id'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:100'],
            'urutan' => ['required', 'integer'],
        ]);

        $pegawai->nip = $validated['nip'];
        $pegawai->nik = '-';
        $pegawai->pangkat_id = 1;
        $pegawai->golongan_id = 1;
        $pegawai->user_id = (int) $validated['user_id'];
        $pegawai->gelar_depan = $validated['gelar_depan'] ?? null;
        $pegawai->gelar_belakang = $validated['gelar_belakang'] ?? null;
        $pegawai->status_nikah = '-';
        $pegawai->shift_id = (int) $validated['shift_id'];
        $pegawai->npwp = '-';
        $pegawai->urutan = (int) $validated['urutan'];
        $pegawai->is_serdos = 0;
        $pegawai->is_remun = 0;
        $pegawai->kampus_id = 1;
        $pegawai->atasan_langsung_id = (int) $validated['atasan_langsung_id'];
        $pegawai->save();

        return redirect('/pegawais');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        $pegawai->delete();

        return redirect('/pegawais');
    }
}
