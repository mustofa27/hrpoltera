<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Lupa Absen</title>
</head>
<body>
    <h1>Validasi Pengajuan Lupa Absen Bawahan</h1>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @elseif(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Aktivitas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
                <tr>
                    <td>{{ $d->user->nama ?? '-' }}</td>
                    <td>{{ $d->tanggal_lupa }}</td>
                    <td>{{ $d->jam_masuk }}</td>
                    <td>{{ $d->jam_pulang }}</td>
                    <td>{{ $d->aktivitas }}</td>
                    <td>
                        <form method="POST" action="{{ route('lupa_absens.update', $d->id) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit">Setujui</button>
                        </form>
                        <form method="POST" action="{{ route('lupa_absens.destroy', $d->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Tolak</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
