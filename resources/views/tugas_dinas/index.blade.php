@extends('layouts.app')
@section('title', 'Tugas Dinas')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Tambah Tugas Dinas</h2>
        <form method="POST" action="{{ route('tugas_dinas.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">No. Surat</label>
                <input type="text" name="no_surat" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tentang</label>
                <input type="text" name="tentang" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
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
                <label class="block text-sm font-medium text-slate-700 mb-1">Pegawai</label>
                <select name="user_id" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($data['users'] as $u)
                        <option value="{{ $u->id }}">{{ $u->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">File Surat Tugas</label>
                <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-6 rounded-lg transition-colors">Simpan</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Daftar Tugas Dinas</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">No. Surat</th>
                        <th class="px-5 py-3 text-left font-semibold">Tentang</th>
                        <th class="px-5 py-3 text-left font-semibold">Pegawai</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal Mulai</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal Selesai</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data['all'] as $td)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-700 font-mono text-xs">{{ $td->surat->no_surat ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-700 max-w-xs truncate">{{ $td->tentang }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $td->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $td->tanggal_mulai }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $td->tanggal_selesai }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('tugas_dinas.destroy', $td) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">Belum ada data tugas dinas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
