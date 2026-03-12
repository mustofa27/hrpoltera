<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\JenisCuti;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CutiController extends Controller
{
    public function index(): View
    {
        $data = [
            'jenis_cutis' => JenisCuti::query()->orderBy('nama')->get(),
            'cuti' => Cuti::query()
                ->with(['jenis', 'user'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get(),
        ];

        return view('cutis.index', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_cuti_id' => ['required', 'integer', 'exists:jenis_cutis,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,xls,xlsx,doc,docx', 'max:10240'],
        ]);

        $cuti = new Cuti();
        $cuti->user_id = Auth::id();
        $cuti->jenis_cuti_id = (int) $validated['jenis_cuti_id'];
        $cuti->tanggal_mulai = $validated['tanggal_mulai'];
        $cuti->tanggal_selesai = $validated['tanggal_selesai'];
        $cuti->keterangan = $validated['keterangan'];
        $cuti->file_pendukung = '-';

        if ($request->hasFile('file')) {
            $directory = public_path('file/cuti');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('file');
            $newName = time().'-'.$file->getClientOriginalName();
            $file->move($directory, $newName);
            $cuti->file_pendukung = 'file/cuti/'.$newName;
        }

        $cuti->pertimbangan_atasan = 'requested';
        $cuti->catatan_atasan = '-';
        $cuti->persetujuan_pejabat = 'requested';
        $cuti->catatan_pejabat = '-';
        $cuti->save();

        return redirect('/cutis');
    }

    public function monitorIndex(): View
    {
        $data['cuti'] = Cuti::query()
            ->join('pegawais', 'cutis.user_id', '=', 'pegawais.user_id')
            ->where('pegawais.atasan_langsung_id', Auth::id())
            ->select('cutis.*')
            ->with(['jenis', 'user'])
            ->latest('cutis.updated_at')
            ->get();

        return view('cutis.monitor', compact('data'));
    }

    public function validasiIndex(): View
    {
        $data['cuti'] = Cuti::query()
            ->with(['jenis', 'user'])
            ->where('pertimbangan_atasan', '!=', 'requested')
            ->latest('updated_at')
            ->get();

        return view('cutis.validasi', compact('data'));
    }

    public function validasiCutiBawahan(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'pertimbangan_atasan' => ['required', 'in:accepted,rejected'],
            'catatan_atasan' => ['required', 'string', 'max:255'],
        ]);

        $cuti = Cuti::query()->findOrFail($id);
        Gate::authorize('approve-cuti-atasan', $cuti);

        $cuti->pertimbangan_atasan = $validated['pertimbangan_atasan'];
        $cuti->catatan_atasan = $validated['catatan_atasan'];

        if ($validated['pertimbangan_atasan'] === 'rejected') {
            $cuti->persetujuan_pejabat = 'rejected';
            $cuti->catatan_pejabat = 'Ditolak Atasan Langsung';
        }

        $cuti->save();

        return redirect()->back();
    }

    public function validasiCutiPejabat(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'persetujuan_pejabat' => ['required', 'in:accepted,rejected'],
            'catatan_pejabat' => ['required', 'string', 'max:255'],
        ]);

        $cuti = Cuti::query()->findOrFail($id);
        Gate::authorize('approve-cuti-pejabat', $cuti);

        $cuti->persetujuan_pejabat = $validated['persetujuan_pejabat'];
        $cuti->catatan_pejabat = $validated['catatan_pejabat'];
        $cuti->save();

        return redirect()->back();
    }

    public function destroy(Cuti $cuti): RedirectResponse
    {
        if ((int) $cuti->user_id === (int) Auth::id() && $cuti->pertimbangan_atasan === 'requested') {
            $cuti->delete();
        }

        return redirect()->back();
    }
}
