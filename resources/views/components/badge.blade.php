@php
$map = [
    'requested' => 'bg-amber-100 text-amber-700 border-amber-200',
    'accepted'  => 'bg-green-100 text-green-700 border-green-200',
    'rejected'  => 'bg-red-100 text-red-700 border-red-200',
    'Selesai'   => 'bg-green-100 text-green-700 border-green-200',
    'Tidak Selesai' => 'bg-red-100 text-red-700 border-red-200',
];
$cls = $map[$status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
@endphp
<span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $cls }}">{{ $status }}</span>
