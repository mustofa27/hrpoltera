<?php

namespace App\Http\Controllers;

use App\Models\Pangkat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PangkatController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Pangkat',
            'storeRoute' => 'pangkats.store',
            'deleteRoute' => 'pangkats.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'text'],
            ],
            'columns' => ['nama', 'deskripsi'],
            'rows' => Pangkat::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        $pangkat = new Pangkat();
        $pangkat->nama = $validated['nama'];
        $pangkat->deskripsi = $validated['deskripsi'];
        $pangkat->save();

        return redirect('/pangkats');
    }

    public function destroy(Pangkat $pangkat): RedirectResponse
    {
        $pangkat->delete();

        return redirect()->back();
    }
}
