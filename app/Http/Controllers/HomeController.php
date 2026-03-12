<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function indexSurat(): View
    {
        $data = Surat::query()->latest()->get();

        return view('surat', compact('data'));
    }

    public function createsurat(): View
    {
        return view('form_surat');
    }

    public function storeSurat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pengirim' => ['required', 'string', 'max:255'],
            'tanggal_surat' => ['required', 'string', 'max:50'],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'perihal' => ['required', 'string', 'max:255'],
            'nomor_agenda' => ['required', 'string', 'max:255'],
            'tanggal_terima' => ['required', 'string', 'max:50'],
            'sifat_surat' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:10240'],
        ]);

        $surat = new Surat();
        $surat->pengirim = $validated['pengirim'];
        $surat->tanggal_surat = $validated['tanggal_surat'];
        $surat->no_surat = $validated['nomor_surat'];
        $surat->perihal = $validated['perihal'];
        $surat->nomor_agenda = $validated['nomor_agenda'];
        $surat->tanggal_terima = $validated['tanggal_terima'];
        $surat->sifat_surat = $validated['sifat_surat'];
        $surat->file_path = '-';

        if ($request->hasFile('file')) {
            $directory = public_path('file/surat');

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('file');
            $newName = time().'-'.$file->getClientOriginalName();
            $file->move($directory, $newName);
            $surat->file_path = 'file/surat/'.$newName;
        }

        $surat->save();

        return redirect('/surat');
    }

    public function indexDisposisi(): View
    {
        $data = Disposisi::query()
            ->join('users', 'users.id', '=', 'disposisis.id_user_tujuan')
            ->join('surats', 'surats.id', '=', 'disposisis.id_surat')
            ->join('users as user1', 'user1.id', '=', 'disposisis.id_user_asal')
            ->select(
                'disposisis.*',
                'surats.pengirim as surat',
                'surats.file_path as file',
                'users.nama as user',
                'user1.nama as userasal'
            )
            ->latest('disposisis.id')
            ->get();

        return view('disposisi', compact('data'));
    }

    public function createDisposisi(): View
    {
        $data['user'] = User::query()->where('id', '!=', Auth::id())->get();
        $disposisiSuratIds = Disposisi::query()->pluck('id_surat');
        $data['surat'] = Surat::query()->whereNotIn('id', $disposisiSuratIds)->get();

        return view('form_disposisi', compact('data'));
    }

    public function storeDisposisi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_disposisi' => ['required', 'string', 'max:50'],
            'surat' => ['required', 'integer', 'exists:surats,id'],
            'user' => ['required', 'integer', 'exists:users,id'],
        ]);

        $disposisi = new Disposisi();
        $disposisi->isi_disposisi = '-';
        $disposisi->tanggal_disposisi = $validated['tanggal_disposisi'];
        $disposisi->tanggal_diterima = '';
        $disposisi->catatan_tindak_lanjut = '';
        $disposisi->id_surat = $validated['surat'];
        $disposisi->id_user_asal = Auth::id();
        $disposisi->id_user_tujuan = $validated['user'];
        $disposisi->save();

        return redirect('/disposisi');
    }

    public function destroyDisposisi(int $id): RedirectResponse
    {
        Disposisi::query()->where('id', $id)->delete();

        return redirect('/disposisi');
    }
}
