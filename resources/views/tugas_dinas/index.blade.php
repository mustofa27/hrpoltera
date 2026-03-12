<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Dinas</title>
</head>
<body>
    <h1>Data Tugas Dinas</h1>

    <form method="POST" action="{{ route('tugas_dinas.store') }}" enctype="multipart/form-data">
        @csrf
        <label>No. Surat</label>
        <input type="text" name="no_surat" required>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" required>

        <label>Tentang</label>
        <input type="text" name="tentang" required>

        <label>Keterangan</label>
        <input type="text" name="keterangan" required>

        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" required>

        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" required>

        <label>Pegawai</label>
        <select name="user_id" required>
            @foreach($data['users'] as $u)
                <option value="{{ $u->id }}">{{ $u->nama }}</option>
            @endforeach
        </select>

        <label>File Surat Tugas</label>
        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.doc,.docx">

        <button type="submit">Simpan</button>
    </form>

    <h2>Daftar Tugas Dinas</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>No. Surat</th>
                <th>Tentang</th>
                <th>Pegawai</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['all'] as $td)
                <tr>
                    <td>{{ $td->surat->no_surat ?? '-' }}</td>
                    <td>{{ $td->tentang }}</td>
                    <td>{{ $td->user->nama ?? '-' }}</td>
                    <td>{{ $td->tanggal_mulai }}</td>
                    <td>{{ $td->tanggal_selesai }}</td>
                    <td>
                        <form method="POST" action="{{ route('tugas_dinas.destroy', $td) }}">
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
