@extends('layouts.app')
@section('title', $title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-base font-semibold text-slate-700 mb-4">Tambah {{ $title }}</h2>
        <form method="POST" action="{{ route($storeRoute) }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            @foreach($fields as $field)
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">{{ $field['label'] }}</label>
                    <input
                        type="{{ $field['type'] }}"
                        name="{{ $field['name'] }}"
                        @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                        required
                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    >
                </div>
            @endforeach
            <div class="flex items-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm py-2 px-6 rounded-lg transition-colors">Simpan</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Data {{ $title }}</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        @foreach($columns as $column)
                            <th class="px-5 py-3 text-left font-semibold">{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                        @endforeach
                        <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($rows as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            @foreach($columns as $column)
                                <td class="px-5 py-3 text-slate-700">{{ $row->{$column} }}</td>
                            @endforeach
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route($deleteRoute, $row) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium border border-red-200 px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ count($columns) + 1 }}" class="px-5 py-8 text-center text-slate-400 italic">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
