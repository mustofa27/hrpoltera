@extends('layouts.app')
@section('title', 'Pengajuan Lupa Absen')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Form Pengajuan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Pengajuan Lupa Absen</h2>
        <form method="POST" action="{{ route('lupa_absens.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Aktivitas</label>
                <input type="text" name="aktivitas" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jam Masuk (HH:MM)</label>
                <input type="time" name="jam_masuk" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jam Pulang (HH:MM)</label>
                <input type="time" name="jam_pulang" required class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-6 rounded-lg transition-colors">Ajukan</button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Riwayat Pengajuan Lupa Absen</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Pulang</th>
                        <th class="px-5 py-3 text-left font-semibold">Aktivitas</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data as $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-700">{{ $d->tanggal_lupa }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $d->jam_masuk }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $d->jam_pulang }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-xs truncate">{{ $d->aktivitas }}</td>
                            <td class="px-5 py-3"><x-badge :status="$d->status" /></td>
                            <td class="px-5 py-3">
                                @if($d->status === 'requested')
                                    <form method="POST" action="{{ route('lupa_absens.destroy', $d->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">Belum ada pengajuan lupa absen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
