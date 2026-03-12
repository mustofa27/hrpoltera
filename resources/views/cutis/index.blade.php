<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuti</title>
</head>
<body>
    <h1>Pengajuan Ijin Tidak Masuk</h1>

    <form method="POST" action="{{ route('cutis.store') }}" enctype="multipart/form-data">
        @csrf
        <label>Jenis Ijin</label>
        <select name="jenis_cuti_id" required>
            @foreach($data['jenis_cutis'] as $jenis)
                <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
            @endforeach
        </select>

        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" required>

        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" required>

        <label>Keterangan</label>
        <input type="text" name="keterangan" required>

        <label>File Pendukung</label>
        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.xls,.xlsx,.doc,.docx">

        <button type="submit">Submit</button>
    </form>

    <h2>List Data Pengajuan Ijin</h2>
    <a href="{{ url('/monitor_cuti') }}">Monitor Cuti</a>
    <a href="{{ url('/validasi_cuti') }}">Validasi Cuti</a>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Jenis Ijin</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Keterangan</th>
                <th>Status Atasan</th>
                <th>Status Pejabat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['cuti'] as $cuti)
                <tr>
                    <td>{{ $cuti->jenis->nama ?? '-' }}</td>
                    <td>{{ $cuti->tanggal_mulai }}</td>
                    <td>{{ $cuti->tanggal_selesai }}</td>
                    <td>{{ $cuti->keterangan }}</td>
                    <td>{{ $cuti->pertimbangan_atasan }}</td>
                    <td>{{ $cuti->persetujuan_pejabat }}</td>
                    <td>
                        @if($cuti->pertimbangan_atasan === 'requested')
                            <form method="POST" action="{{ route('cutis.destroy', $cuti) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
