<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitKerjaController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Unit Kerja',
            'storeRoute' => 'unit_kerjas.store',
            'deleteRoute' => 'unit_kerjas.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'text'],
                ['name' => 'tipe', 'label' => 'Tipe', 'type' => 'number', 'step' => '1'],
            ],
            'columns' => ['nama', 'deskripsi', 'tipe'],
            'rows' => UnitKerja::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tipe' => ['required', 'integer'],
        ]);

        $unitKerja = new UnitKerja();
        $unitKerja->nama = $validated['nama'];
        $unitKerja->deskripsi = $validated['deskripsi'];
        $unitKerja->tipe = (int) $validated['tipe'];
        $unitKerja->save();

        return redirect('/unit_kerjas');
    }

    public function destroy(UnitKerja $unitKerja): RedirectResponse
    {
        $unitKerja->delete();

        return redirect()->back();
    }
}
