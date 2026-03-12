@extends('layouts.app')
@section('title', 'Data Pegawai')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Tambah Data Pegawai</h2>
        <form method="POST" action="{{ route('pegawais.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">User</label>
                <select name="user_id" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">NIP</label>
                <input type="text" name="nip" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Shift</label>
                <select name="shift_id" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($shifts as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Atasan Langsung</label>
                <select name="atasan_langsung_id" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Depan</label>
                <input type="text" name="gelar_depan" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Gelar Belakang</label>
                <input type="text" name="gelar_belakang" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Urutan</label>
                <input type="number" name="urutan" required min="1" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-6 rounded-lg transition-colors">Simpan</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Daftar Pegawai</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Nama</th>
                        <th class="px-5 py-3 text-left font-semibold">NIP</th>
                        <th class="px-5 py-3 text-left font-semibold">Gelar</th>
                        <th class="px-5 py-3 text-left font-semibold">Atasan</th>
                        <th class="px-5 py-3 text-left font-semibold">Shift</th>
                        <th class="px-5 py-3 text-left font-semibold">Urutan</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($pegawai as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $p->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $p->nip }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ trim(($p->gelar_depan ?? '').' '.($p->gelar_belakang ?? '')) }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->atasan->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->shift->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->urutan }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('pegawais.destroy', $p) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400 italic">Belum ada data pegawai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
