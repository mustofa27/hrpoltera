@extends('layouts.app')
@section('title', 'Aktivitas Harian')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Tambah Aktivitas Hari Ini</h2>

        <form method="POST" action="{{ route('aktivitas_harians.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kegiatan</label>
                <input type="text" name="kegiatan" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <option>Selesai</option>
                    <option>Tidak Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">File Pendukung</label>
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-5 rounded-lg transition-colors">Simpan Aktivitas</button>
            </div>
        </form>
    </div>

    {{-- Action links --}}
    <div class="flex flex-wrap gap-3 items-center">
        <a href="{{ url('/monitor') }}" class="inline-flex items-center gap-1.5 text-sm text-indigo-600 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-medium transition-colors">Monitor Bawahan</a>
        <a href="{{ url('/aktivitas/past_date') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-600 border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg font-medium transition-colors">Tambah Data Lampau</a>

        <form method="POST" action="{{ url('/cetak_pdf/pribadi') }}" class="flex items-center gap-2 ml-auto">
            @csrf
            <input type="date" name="tanggal" required class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm outline-none focus:border-indigo-500">
            <button type="submit" class="text-sm bg-slate-700 hover:bg-slate-800 text-white font-medium px-3 py-1.5 rounded-lg transition-colors">Export PDF</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Daftar Aktivitas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam</th>
                        <th class="px-5 py-3 text-left font-semibold">Kegiatan</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">File</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-700">{{ $item->tanggal }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $item->jam }}</td>
                            <td class="px-5 py-3 text-slate-700 max-w-xs truncate">{{ $item->kegiatan }}</td>
                            <td class="px-5 py-3"><x-badge :status="$item->status" /></td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ $item->file_pendukung }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('aktivitas_harians.destroy', $item) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">Belum ada aktivitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
