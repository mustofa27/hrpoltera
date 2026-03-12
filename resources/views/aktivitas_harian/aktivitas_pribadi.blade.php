<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aktivitas Pribadi</title>
</head>
<body>
    <h3>Laporan Aktivitas Pribadi</h3>
    <p>Tanggal: {{ $tanggal }}</p>
    <p>Pegawai: {{ auth()->user()->nama }}</p>

    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Uraian Pekerjaan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aktivitas as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->kegiatan }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Atasan: {{ $atasan->nama ?? '-' }}</p>
</body>
</html>
