<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Harian</title>
</head>
<body>
    <h1>Tambah Aktivitas</h1>

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('aktivitas_harians.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Kegiatan</label>
        <input type="text" name="kegiatan" required>

        <label>Status</label>
        <select name="status" required>
            <option>Selesai</option>
            <option>Tidak Selesai</option>
        </select>

        <label>File Pendukung</label>
        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.doc,.docx">

        <button type="submit">Submit</button>
    </form>

    <h2>List Data Aktivitas</h2>
    <a href="{{ url('/monitor') }}">Monitor</a>
    <a href="{{ url('/aktivitas/past_date') }}">Tambah Data Lampau</a>

    <form method="POST" action="{{ url('/cetak_pdf/pribadi') }}">
        @csrf
        <label>Tanggal PDF</label>
        <input type="date" name="tanggal" required>
        <button type="submit">Export PDF Pribadi</button>
    </form>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kegiatan</th>
                <th>Status</th>
                <th>File Pendukung</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->kegiatan }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->file_pendukung }}</td>
                    <td>
                        <form method="POST" action="{{ route('aktivitas_harians.destroy', $item) }}">
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
