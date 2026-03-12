@extends('layouts.app')
@section('title', 'Absensi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-base font-semibold text-slate-700 mb-4">Absen Masuk</h2>
            @if(empty($data['current']))
                <form method="POST" action="{{ route('absensis.store') }}">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2.5 px-4 rounded-lg transition-colors">Check In Sekarang</button>
                </form>
            @else
                <p class="text-sm text-slate-500 mb-1">Sudah check in pada</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $data['current']->jam_masuk }}</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-base font-semibold text-slate-700 mb-4">Absen Pulang</h2>
            @if(!empty($data['current']) && $data['current']->jam_pulang === '-')
                <form method="POST" action="{{ route('absensis.pulang') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $data['current']->id }}">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm py-2.5 px-4 rounded-lg transition-colors">Check Out Sekarang</button>
                </form>
            @elseif(!empty($data['current']))
                <p class="text-sm text-slate-500 mb-1">Sudah check out pada</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $data['current']->jam_pulang }}</p>
            @else
                <p class="text-sm text-slate-400 italic">Belum check in hari ini.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-700">Riwayat Absensi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Pulang</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data['all'] as $absen)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-700">{{ $absen->tanggal }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $absen->jam_masuk }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $absen->jam_pulang }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('absensis.destroy', $absen) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400 italic">Belum ada data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
