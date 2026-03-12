<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use DateTime;
use DateTimeZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(): View
    {
        $today = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $userId = (int) Auth::id();

        $all = Absensi::query()
            ->where('user_id', $userId)
            ->orderByDesc('tanggal')
            ->limit(60)
            ->get();

        $current = Absensi::query()
            ->where('user_id', $userId)
            ->where('tanggal', $today->format('Y-m-d'))
            ->first();

        return view('absensis.index', [
            'data' => [
                'all' => $all,
                'current' => $current,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $today = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $user = Auth::user();

        $existing = Absensi::query()
            ->where('user_id', $user->id)
            ->where('tanggal', $today->format('Y-m-d'))
            ->first();

        if ($existing !== null) {
            return redirect('/absensis');
        }

        $absensi = new Absensi();
        $absensi->user_id = $user->id;
        $absensi->tanggal = $today->format('Y-m-d');
        $absensi->jam_masuk = $today->format('H:i:s');
        $absensi->jam_pulang = '-';
        $absensi->kampus_id = 1;
        $absensi->terlambat = 0;
        $absensi->pulang_sebelum_waktu = 0;
        $absensi->alasan_ganti_kampus = '-';
        $absensi->ttd_masuk_path = $this->storeSignature((string) $request->input('img'), (string) $user->nama) ?? '-';
        $absensi->ttd_pulang_path = '-';
        $absensi->save();

        return redirect('/absensis');
    }

    public function pulang(Request $request): JsonResponse
    {
        $absensi = Absensi::query()->findOrFail((int) $request->input('id'));

        if ((int) $absensi->user_id !== (int) Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $absensi->jam_pulang = $now->format('H:i:s');
        $absensi->ttd_pulang_path = $this->storeSignature((string) $request->input('img'), (string) Auth::user()->nama) ?? '-';
        $absensi->save();

        return response()->json(['success' => true, 'message' => 'Berhasil']);
    }

    public function destroy(Absensi $absensi): RedirectResponse
    {
        if ((int) $absensi->user_id === (int) Auth::id()) {
            $absensi->delete();
        }

        return redirect('/absensis');
    }

    public function monitor(): View
    {
        $user = Auth::user();
        $query = Pegawai::query()->with(['user', 'user.unit', 'absen']);

        if ($user->username !== 'adminpusat') {
            $query->where('atasan_langsung_id', $user->id);
        } else {
            $query->where('user_id', '!=', $user->id);
        }

        $pegawais = $query->orderBy('urutan')->get();

        return view('absensis.monitor', [
            'data' => [
                'unit' => UnitKerja::all(),
                'pegawais' => $pegawais,
            ],
        ]);
    }

    public function exportExcel(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Export endpoint is active for Absensi module migration.',
            'from' => $request->input('tanggal_mulai'),
            'to' => $request->input('tanggal_selesai'),
        ]);
    }

    public function exportExcelSendiri(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Self export endpoint is active for Absensi module migration.',
            'from' => $request->input('tanggal_mulai'),
            'to' => $request->input('tanggal_selesai'),
        ]);
    }

    private function storeSignature(string $dataUri, string $name): ?string
    {
        if ($dataUri === '' || ! str_contains($dataUri, ',')) {
            return null;
        }

        [$meta, $encoded] = explode(',', $dataUri, 2);

        if (! str_contains($meta, 'base64')) {
            return null;
        }

        $decoded = base64_decode($encoded, true);

        if ($decoded === false) {
            return null;
        }

        $directory = public_path('file/absensis');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = sprintf('%s-%s.png', preg_replace('/[^A-Za-z0-9\-_]/', '-', $name), time());
        $target = $directory.'/'.$filename;
        file_put_contents($target, $decoded);

        return 'file/absensis/'.$filename;
    }
}
