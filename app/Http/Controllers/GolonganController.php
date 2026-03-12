<?php

namespace App\Http\Controllers;

use App\Models\Golongan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GolonganController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Golongan',
            'storeRoute' => 'golongans.store',
            'deleteRoute' => 'golongans.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'text'],
            ],
            'columns' => ['nama', 'deskripsi'],
            'rows' => Golongan::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        $golongan = new Golongan();
        $golongan->nama = $validated['nama'];
        $golongan->deskripsi = $validated['deskripsi'];
        $golongan->save();

        return redirect('/golongans');
    }

    public function destroy(Golongan $golongan): RedirectResponse
    {
        $golongan->delete();

        return redirect()->back();
    }
}
