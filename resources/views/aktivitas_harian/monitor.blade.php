@extends('layouts.app')
@section('title', 'Monitor Aktivitas Bawahan')

@section('content')
<div class="max-w-6xl mx-auto space-y-5">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Export PDF Bawahan</h2>
        <form method="POST" action="{{ url('/cetak_pdf/bawahan') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white font-medium text-sm py-2 px-5 rounded-lg transition-colors">Export PDF</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Aktivitas Bawahan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Pegawai</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam</th>
                        <th class="px-5 py-3 text-left font-semibold">Kegiatan</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">File</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $item->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $item->tanggal }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $item->jam }}</td>
                            <td class="px-5 py-3 text-slate-700 max-w-xs truncate">{{ $item->kegiatan }}</td>
                            <td class="px-5 py-3"><x-badge :status="$item->status" /></td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ $item->file_pendukung }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
