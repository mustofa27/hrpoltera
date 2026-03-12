<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AktivitasHarian;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AktivitasHarianController extends Controller
{
    public function index(): View
    {
        $data = AktivitasHarian::query()
            ->where('user_id', Auth::id())
            ->latest('tanggal')
            ->latest('jam')
            ->get();

        return view('aktivitas_harian.index', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan' => ['required', 'string'],
            'status' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,xls,xlsx,doc,docx', 'max:10240'],
        ]);

        $today = now('Asia/Jakarta')->toDateString();
        $hasAbsensi = Absensi::query()
            ->where('tanggal', $today)
            ->where('user_id', Auth::id())
            ->exists();

        if (! $hasAbsensi) {
            return redirect('/aktivitas_harians')
                ->with('error', 'Penambahan aktivitas gagal. Silahkan absen terlebih dahulu di menu absensi');
        }

        $aktivitas = new AktivitasHarian();
        $aktivitas->user_id = Auth::id();
        $aktivitas->tanggal = $today;
        $aktivitas->jam = now('Asia/Jakarta')->format('H:i:s');
        $aktivitas->kegiatan = $validated['kegiatan'];
        $aktivitas->status = $validated['status'];
        $aktivitas->file_pendukung = '-';

        if ($request->hasFile('file')) {
            $directory = public_path('file/aktivitas_harian');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('file');
            $newName = time().'-'.$file->getClientOriginalName();
            $file->move($directory, $newName);
            $aktivitas->file_pendukung = 'file/aktivitas_harian/'.$newName;
        }

        $aktivitas->save();

        return redirect('/aktivitas_harians');
    }

    public function destroy(AktivitasHarian $aktivitasHarian): RedirectResponse
    {
        if ((int) $aktivitasHarian->user_id === (int) Auth::id()) {
            $aktivitasHarian->delete();
        }

        return redirect()->back();
    }

    public function monitor(): View
    {
        $today = now('Asia/Jakarta')->toDateString();

        if (Auth::user()?->username === 'adminpusat') {
            $data = AktivitasHarian::query()
                ->join('users', 'users.id', '=', 'aktivitas_harians.user_id')
                ->join('pegawais', 'aktivitas_harians.user_id', '=', 'pegawais.user_id')
                ->where('aktivitas_harians.user_id', '!=', Auth::id())
                ->where('aktivitas_harians.tanggal', $today)
                ->select('aktivitas_harians.*')
                ->with('user')
                ->orderBy('users.unit_kerja_id')
                ->orderBy('pegawais.urutan')
                ->orderBy('aktivitas_harians.tanggal')
                ->get();
        } else {
            $atasanId = Auth::id();
            $data = AktivitasHarian::query()
                ->with('user')
                ->whereHas('user.pegawai', function ($query) use ($atasanId) {
                    $query->where('atasan_langsung_id', $atasanId);
                })
                ->where('user_id', '!=', $atasanId)
                ->where('tanggal', $today)
                ->orderBy('user_id')
                ->orderBy('tanggal')
                ->get();
        }

        return view('aktivitas_harian.monitor', compact('data'));
    }

    public function createByDate(): View
    {
        return view('aktivitas_harian.create_by_date');
    }

    public function storeByDate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam' => ['required', 'date_format:H:i:s'],
            'kegiatan' => ['required', 'string'],
            'status' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,xls,xlsx,doc,docx', 'max:10240'],
        ]);

        $aktivitas = new AktivitasHarian();
        $aktivitas->user_id = Auth::id();
        $aktivitas->tanggal = $validated['tanggal'];
        $aktivitas->jam = $validated['jam'];
        $aktivitas->kegiatan = $validated['kegiatan'];
        $aktivitas->status = $validated['status'];
        $aktivitas->file_pendukung = '-';

        if ($request->hasFile('file')) {
            $directory = public_path('file/aktivitas_harian');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('file');
            $newName = time().'-'.$file->getClientOriginalName();
            $file->move($directory, $newName);
            $aktivitas->file_pendukung = 'file/aktivitas_harian/'.$newName;
        }

        $aktivitas->save();

        return redirect('/aktivitas_harians');
    }

    public function cetakPdf(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
        ]);

        $date = $validated['tanggal'];
        $aktivitas = AktivitasHarian::query()
            ->where('tanggal', $date)
            ->where('user_id', Auth::id())
            ->get();

        $atasan = User::query()->with('pegawai')->find(optional(Auth::user()->pegawai)->atasan_langsung_id);

        return Pdf::loadView('aktivitas_harian.aktivitas_pribadi', [
            'aktivitas' => $aktivitas,
            'tanggal' => $date,
            'atasan' => $atasan,
        ])->download(Auth::user()->nama.'_aktivitas_'.$date.'.pdf');
    }

    public function cetakPdfBawahan(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
        ]);

        $tanggalMulai = $validated['tanggal_mulai'];
        $tanggalSelesai = $validated['tanggal_selesai'];

        if (Auth::user()?->username === 'adminpusat') {
            $aktivitas = AktivitasHarian::query()
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                ->join('users', 'users.id', '=', 'aktivitas_harians.user_id')
                ->join('pegawais', 'aktivitas_harians.user_id', '=', 'pegawais.user_id')
                ->where('aktivitas_harians.user_id', '!=', Auth::id())
                ->select('aktivitas_harians.*')
                ->with('user')
                ->orderBy('users.unit_kerja_id')
                ->orderBy('pegawais.urutan')
                ->orderBy('aktivitas_harians.tanggal')
                ->get();
        } else {
            $atasanId = Auth::id();
            $aktivitas = AktivitasHarian::query()
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                ->with('user')
                ->whereHas('user.pegawai', function ($query) use ($atasanId) {
                    $query->where('atasan_langsung_id', $atasanId);
                })
                ->where('user_id', '!=', $atasanId)
                ->orderBy('user_id')
                ->orderBy('tanggal')
                ->get();
        }

        return Pdf::loadView('aktivitas_harian.aktivitas_bawahan', [
            'aktivitas' => $aktivitas,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
        ])->download('monitor_'.$tanggalMulai.'-'.$tanggalSelesai.'.pdf');
    }
}
