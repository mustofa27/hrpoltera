@extends('layouts.app')
@section('title', 'Validasi Lupa Absen Bawahan')

@section('content')
<div class="max-w-5xl mx-auto space-y-4">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Validasi Pengajuan Lupa Absen Bawahan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">Pegawai</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3 text-left font-semibold">Jam Pulang</th>
                        <th class="px-5 py-3 text-left font-semibold">Aktivitas</th>
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($data as $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $d->user->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $d->tanggal_lupa }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $d->jam_masuk }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $d->jam_pulang }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-xs truncate">{{ $d->aktivitas }}</td>
                            <td class="px-5 py-3">
                                <div class="flex flex-col gap-1.5">
                                    <form method="POST" action="{{ route('lupa_absens.update', $d->id) }}" class="inline">
                                        @csrf @method('PUT')
                                        <button type="submit" class="w-full text-xs bg-green-600 hover:bg-green-700 text-white font-medium px-3 py-1 rounded-lg transition-colors">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('lupa_absens.destroy', $d->id) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-3 py-1 rounded-lg hover:bg-red-50 transition-colors">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400 italic">Tidak ada pengajuan yang perlu divalidasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
