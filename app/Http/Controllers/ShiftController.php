<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function index(): View
    {
        return view('master_data.index', [
            'title' => 'Shift',
            'storeRoute' => 'shifts.store',
            'deleteRoute' => 'shifts.destroy',
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text'],
                ['name' => 'jam_mulai', 'label' => 'Jam Mulai', 'type' => 'time'],
                ['name' => 'jam_mulai_maks', 'label' => 'Jam Mulai Maks', 'type' => 'time'],
                ['name' => 'jam_selesai', 'label' => 'Jam Selesai', 'type' => 'time'],
                ['name' => 'durasi_minimum', 'label' => 'Durasi Minimum', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'min_aktivitas', 'label' => 'Min Aktivitas', 'type' => 'number', 'step' => '1'],
            ],
            'columns' => ['nama', 'jam_mulai', 'jam_mulai_maks', 'jam_selesai', 'durasi_minimum', 'min_aktivitas'],
            'rows' => Shift::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_mulai_maks' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i'],
            'durasi_minimum' => ['required', 'numeric'],
            'min_aktivitas' => ['required', 'integer'],
        ]);

        $shift = new Shift();
        $shift->nama = $validated['nama'];
        $shift->jam_mulai = $validated['jam_mulai'];
        $shift->jam_mulai_maks = $validated['jam_mulai_maks'];
        $shift->jam_selesai = $validated['jam_selesai'];
        $shift->durasi_minimum = (float) $validated['durasi_minimum'];
        $shift->min_aktivitas = (int) $validated['min_aktivitas'];
        $shift->save();

        return redirect('/shifts');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        $shift->delete();

        return redirect()->back();
    }
}
