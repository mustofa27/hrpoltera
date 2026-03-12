<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Aktivitas Harian</title>
</head>
<body>
    <h1>Monitor Aktivitas Bawahan</h1>

    <form method="POST" action="{{ url('/cetak_pdf/bawahan') }}">
        @csrf
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" required>

        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" required>

        <button type="submit">Export PDF Bawahan</button>
    </form>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kegiatan</th>
                <th>Status</th>
                <th>File Pendukung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->kegiatan }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->file_pendukung }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
