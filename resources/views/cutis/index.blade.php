@extends('layouts.app')
@section('title', 'Cuti & Ijin')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Form Pengajuan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Pengajuan Ijin Tidak Masuk</h2>
        <form method="POST" action="{{ route('cutis.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Ijin</label>
                <select name="jenis_cuti_id" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($data['jenis_cutis'] as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                <input type="text" name="keterangan" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">File Pendukung</label>
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-6 rounded-lg transition-colors">Ajukan Cuti</button>
            </div>
        </form>
    </div>

    {{-- Action links --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ url('/monitor_cuti') }}" class="inline-flex items-center gap-1.5 text-sm text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-medium transition-colors">Monitor Cuti Bawahan</a>
        <a href="{{ url('/validasi_cuti') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-600 border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg font-medium transition-colors">Validasi Cuti (Pejabat)</a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Daftar Pengajuan Cuti</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Jenis Ijin</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal Mulai</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal Selesai</th>
                        <th class="px-5 py-3 text-left font-semibold">Keterangan</th>
                        <th class="px-5 py-3 text-left font-semibold">Status Atasan</th>
                        <th class="px-5 py-3 text-left font-semibold">Status Pejabat</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data['cuti'] as $cuti)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $cuti->jenis->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $cuti->tanggal_mulai }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $cuti->tanggal_selesai }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-xs truncate">{{ $cuti->keterangan }}</td>
                            <td class="px-5 py-3"><x-badge :status="$cuti->pertimbangan_atasan" /></td>
                            <td class="px-5 py-3"><x-badge :status="$cuti->persetujuan_pejabat" /></td>
                            <td class="px-5 py-3">
                                @if($cuti->pertimbangan_atasan === 'requested')
                                    <form method="POST" action="{{ route('cutis.destroy', $cuti) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 italic">Belum ada pengajuan cuti.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
