<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\SuratInternal;
use App\Models\TugasDinas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TugasDinasController extends Controller
{
    public function index(): View
    {
        $data = [
            'all' => TugasDinas::query()
                ->with(['user', 'surat'])
                ->latest('updated_at')
                ->get(),
            'pegawai' => Pegawai::query()->with('user')->orderBy('urutan')->get(),
            'users' => User::query()->orderBy('nama')->get(),
        ];

        return view('tugas_dinas.index', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_surat' => ['required', 'string', 'max:100'],
            'tanggal_surat' => ['required', 'date'],
            'tentang' => ['required', 'string'],
            'keterangan' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,xls,xlsx,doc,docx', 'max:10240'],
        ]);

        $suratInternal = new SuratInternal();
        $suratInternal->no_surat = $validated['no_surat'];
        $suratInternal->tanggal_surat = $validated['tanggal_surat'];
        $suratInternal->jenis_surat_id = 1;
        $suratInternal->no_urut_surat = 0;
        $suratInternal->file_pendukung = '-';

        if ($request->hasFile('file')) {
            $directory = public_path('file/surat_internal');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('file');
            $newName = time().'-'.$file->getClientOriginalName();
            $file->move($directory, $newName);
            $suratInternal->file_pendukung = 'file/surat_internal/'.$newName;
        }

        $suratInternal->save();

        $tugasDinas = new TugasDinas();
        $tugasDinas->tentang = $validated['tentang'];
        $tugasDinas->keterangan = $validated['keterangan'];
        $tugasDinas->tanggal_mulai = $validated['tanggal_mulai'];
        $tugasDinas->tanggal_selesai = $validated['tanggal_selesai'];
        $tugasDinas->user_id = (int) $validated['user_id'];
        $tugasDinas->surat_internal_id = $suratInternal->id;
        $tugasDinas->save();

        return redirect('/tugas_dinas');
    }

    public function destroy(TugasDinas $tugasDina): RedirectResponse
    {
        Gate::authorize('delete-tugas-dinas', $tugasDina);

        $tugasDina->delete();

        return redirect()->back();
    }
}
