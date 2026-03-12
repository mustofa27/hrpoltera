@extends('layouts.app')
@section('title', 'Validasi Cuti Pejabat')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
    <a href="{{ url('/cutis') }}" class="inline-flex items-center text-sm text-indigo-600 hover:underline">&larr; Kembali ke Cuti</a>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Validasi Cuti — Pejabat</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Nama Pegawai</th>
                        <th class="px-5 py-3 text-left font-semibold">Jenis Ijin</th>
                        <th class="px-5 py-3 text-left font-semibold">Mulai</th>
                        <th class="px-5 py-3 text-left font-semibold">Selesai</th>
                        <th class="px-5 py-3 text-left font-semibold">Status Atasan</th>
                        <th class="px-5 py-3 text-left font-semibold">Status Pejabat</th>
                        <th class="px-5 py-3 text-left font-semibold">Catatan</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data['cuti'] as $cuti)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $cuti->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $cuti->jenis->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $cuti->tanggal_mulai }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $cuti->tanggal_selesai }}</td>
                            <td class="px-5 py-3"><x-badge :status="$cuti->pertimbangan_atasan" /></td>
                            <td class="px-5 py-3"><x-badge :status="$cuti->persetujuan_pejabat" /></td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ $cuti->catatan_pejabat }}</td>
                            <td class="px-5 py-3">
                                @if($cuti->persetujuan_pejabat === 'requested')
                                    <form method="POST" action="{{ route('cutis.validasi.pejabat', $cuti->id) }}" class="flex flex-col gap-1.5 min-w-[180px]">
                                        @csrf
                                        <select name="persetujuan_pejabat" class="rounded-lg border border-slate-300 px-2 py-1 text-xs outline-none focus:border-indigo-500">
                                            <option value="accepted">Accept</option>
                                            <option value="rejected">Reject</option>
                                        </select>
                                        <input type="text" name="catatan_pejabat" required placeholder="Catatan..." class="rounded-lg border border-slate-300 px-2 py-1 text-xs outline-none focus:border-indigo-500">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium py-1 px-3 rounded-lg transition-colors">Simpan</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-8 text-center text-slate-400 italic">Tidak ada pengajuan yang perlu divalidasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
