<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AktivitasHarian;
use App\Models\LupaAbsen;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LupaAbsenController extends Controller
{
    public function index(): View
    {
        $data = LupaAbsen::query()
            ->where('user_id', Auth::id())
            ->latest('tanggal_lupa')
            ->get();

        return view('lupa_absens.index', compact('data'));
    }

    public function validasi(): View
    {
        $data = LupaAbsen::query()
            ->with('user')
            ->where('atasan_id', Auth::id())
            ->where('status', 'requested')
            ->latest('tanggal_lupa')
            ->get();

        return view('lupa_absens.validasi', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i', 'after:jam_masuk'],
            'aktivitas' => ['required', 'string'],
        ]);

        $monday = Carbon::parse($validated['tanggal'])->startOfWeek(Carbon::MONDAY);
        $friday = Carbon::parse($validated['tanggal'])->endOfWeek(Carbon::FRIDAY);

        $existing = LupaAbsen::query()
            ->where('user_id', Auth::id())
            ->whereBetween('tanggal_lupa', [$monday, $friday])
            ->count();

        if ($existing > 0) {
            return redirect('/lupa_absens')->with('error', 'Kuota Mingguan Habis');
        }

        $atasanId = optional(Auth::user()->pegawai)->atasan_langsung_id ?? Auth::id();

        $lupaAbsen = new LupaAbsen();
        $lupaAbsen->tanggal_lupa = $validated['tanggal'];
        $lupaAbsen->jam_masuk = $validated['jam_masuk'];
        $lupaAbsen->jam_pulang = $validated['jam_pulang'];
        $lupaAbsen->aktivitas = $validated['aktivitas'];
        $lupaAbsen->status = 'requested';
        $lupaAbsen->user_id = Auth::id();
        $lupaAbsen->atasan_id = $atasanId;
        $lupaAbsen->save();

        return redirect('/lupa_absens')->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function update(Request $request, LupaAbsen $lupaAbsen): RedirectResponse
    {
        Gate::authorize('approve-lupa-absen', $lupaAbsen);

        $absensi = Absensi::query()
            ->where('tanggal', $lupaAbsen->tanggal_lupa)
            ->where('user_id', $lupaAbsen->user_id)
            ->first();

        if (! $absensi) {
            $absensi = new Absensi();
            $absensi->ttd_masuk_path = '-';
        }

        $absensi->user_id = $lupaAbsen->user_id;
        $absensi->tanggal = $lupaAbsen->tanggal_lupa;
        $absensi->jam_masuk = $lupaAbsen->jam_masuk;
        $absensi->jam_pulang = $lupaAbsen->jam_pulang;
        $absensi->kampus_id = 1;
        $absensi->terlambat = 0;
        $absensi->pulang_sebelum_waktu = 0;
        $absensi->alasan_ganti_kampus = '-';
        $absensi->ttd_pulang_path = $absensi->ttd_masuk_path;

        $aktivitas = new AktivitasHarian();
        $aktivitas->user_id = $lupaAbsen->user_id;
        $aktivitas->tanggal = $lupaAbsen->tanggal_lupa;
        $aktivitas->jam = $lupaAbsen->jam_masuk;
        $aktivitas->kegiatan = $lupaAbsen->aktivitas;
        $aktivitas->status = 'Selesai';
        $aktivitas->file_pendukung = '-';

        DB::transaction(function () use ($aktivitas, $absensi, $lupaAbsen) {
            $aktivitas->save();
            $absensi->save();
            $lupaAbsen->status = 'accepted';
            $lupaAbsen->save();
        });

        return redirect('/validasi_lupa_absen')->with('success', 'Pengajuan berhasil disetujui');
    }

    public function destroy(LupaAbsen $lupaAbsen): RedirectResponse
    {
        if ((int) Auth::id() === (int) $lupaAbsen->user_id && $lupaAbsen->status === 'requested') {
            $lupaAbsen->delete();

            return redirect('/lupa_absens');
        }

        Gate::authorize('reject-lupa-absen', $lupaAbsen);

        $lupaAbsen->status = 'rejected';
        $lupaAbsen->save();

        return redirect('/validasi_lupa_absen');
    }
}
