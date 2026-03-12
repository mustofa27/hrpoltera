<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JabatanController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Jabatan',
            'storeRoute' => 'jabatans.store',
            'deleteRoute' => 'jabatans.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'text'],
            ],
            'columns' => ['nama', 'deskripsi'],
            'rows' => Jabatan::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        $jabatan = new Jabatan();
        $jabatan->nama = $validated['nama'];
        $jabatan->deskripsi = $validated['deskripsi'];
        $jabatan->save();

        return redirect('/jabatans');
    }

    public function destroy(Jabatan $jabatan): RedirectResponse
    {
        $jabatan->delete();

        return redirect()->back();
    }
}
