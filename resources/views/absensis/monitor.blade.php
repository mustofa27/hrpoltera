@extends('layouts.app')
@section('title', 'Monitor Absensi')

@section('content')
<div class="max-w-5xl mx-auto space-y-4">
    <a href="{{ url('/absensis') }}" class="inline-flex items-center text-sm text-indigo-600 hover:underline">&larr; Kembali ke Absensi</a>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Monitor Absensi Hari Ini</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Nama</th>
                        <th class="px-5 py-3 text-left font-semibold">Unit</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Pulang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data['pegawais'] as $pegawai)
                        @php($absensi = $pegawai->absensi)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $pegawai->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $pegawai->user->unit->nama ?? '-' }}</td>
                            <td class="px-5 py-3">
                                @if(empty($absensi))
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium border bg-red-100 text-red-700 border-red-200">Belum Absen</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium border bg-green-100 text-green-700 border-green-200">Sudah Absen</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ empty($absensi) ? '-' : $absensi->jam_masuk }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ empty($absensi) ? '-' : $absensi->jam_pulang }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400 italic">Tidak ada data pegawai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
