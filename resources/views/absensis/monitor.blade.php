<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Absensi</title>
</head>
<body>
    <h1>Monitor Absensi</h1>
    <a href="{{ url('/absensis') }}">Kembali ke Absensi</a>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Unit</th>
                <th>Status</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['pegawais'] as $pegawai)
                @php($absensi = $pegawai->absensi)
                <tr>
                    <td>{{ $pegawai->user->nama ?? '-' }}</td>
                    <td>{{ $pegawai->user->unit->nama ?? '-' }}</td>
                    <td>{{ empty($absensi) ? 'Belum Absen' : 'Sudah Absen' }}</td>
                    <td>{{ empty($absensi) ? '-' : $absensi->jam_masuk }}</td>
                    <td>{{ empty($absensi) ? '-' : $absensi->jam_pulang }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
