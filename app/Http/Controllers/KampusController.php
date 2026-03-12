<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KampusController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Kampus',
            'storeRoute' => 'kampuses.store',
            'deleteRoute' => 'kampuses.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'alamat', 'label' => 'Alamat', 'type' => 'text'],
                ['name' => 'latitude', 'label' => 'Latitude', 'type' => 'number', 'step' => '0.000001'],
                ['name' => 'longitude', 'label' => 'Longitude', 'type' => 'number', 'step' => '0.000001'],
            ],
            'columns' => ['nama', 'alamat', 'latitude', 'longitude'],
            'rows' => Kampus::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $kampus = new Kampus();
        $kampus->nama = $validated['nama'];
        $kampus->alamat = $validated['alamat'];
        $kampus->latitude = (float) $validated['latitude'];
        $kampus->longitude = (float) $validated['longitude'];
        $kampus->save();

        return redirect('/kampuses');
    }

    public function destroy(Kampus $kampus): RedirectResponse
    {
        $kampus->delete();

        return redirect()->back();
    }
}
