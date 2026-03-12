<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HariLiburController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Hari Libur',
            'storeRoute' => 'liburs.store',
            'deleteRoute' => 'liburs.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'tanggal', 'label' => 'Tanggal', 'type' => 'date'],
            ],
            'columns' => ['nama', 'tanggal'],
            'rows' => HariLibur::query()->orderBy('tanggal')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
        ]);

        $libur = new HariLibur();
        $libur->nama = $validated['nama'];
        $libur->tanggal = $validated['tanggal'];
        $libur->save();

        return redirect('/liburs');
    }

    public function destroy(HariLibur $libur): RedirectResponse
    {
        $libur->delete();

        return redirect()->back();
    }
}
