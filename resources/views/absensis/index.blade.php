<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi</title>
</head>
<body>
    <h1>Absensi</h1>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <h2>Absen Masuk</h2>
    @if(empty($data['current']))
        <form method="POST" action="{{ route('absensis.store') }}">
            @csrf
            <button type="submit">Check In</button>
        </form>
    @else
        <p>Sudah check in hari ini pada {{ $data['current']->jam_masuk }}</p>
    @endif

    <h2>Absen Pulang</h2>
    @if(!empty($data['current']) && $data['current']->jam_pulang === '-')
        <form method="POST" action="{{ route('absensis.pulang') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $data['current']->id }}">
            <button type="submit">Check Out</button>
        </form>
    @elseif(!empty($data['current']))
        <p>Sudah check out hari ini pada {{ $data['current']->jam_pulang }}</p>
    @else
        <p>Belum check in.</p>
    @endif

    <h2>Riwayat</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['all'] as $absen)
                <tr>
                    <td>{{ $absen->tanggal }}</td>
                    <td>{{ $absen->jam_masuk }}</td>
                    <td>{{ $absen->jam_pulang }}</td>
                    <td>
                        <form method="POST" action="{{ route('absensis.destroy', $absen) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
