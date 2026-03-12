<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Cuti</title>
</head>
<body>
    <h1>Monitor Cuti Bawahan</h1>
    <a href="{{ url('/cutis') }}">Kembali ke Cuti</a>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th>Jenis Ijin</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Keterangan</th>
                <th>Status Atasan</th>
                <th>Catatan Atasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['cuti'] as $cuti)
                <tr>
                    <td>{{ $cuti->user->nama ?? '-' }}</td>
                    <td>{{ $cuti->jenis->nama ?? '-' }}</td>
                    <td>{{ $cuti->tanggal_mulai }}</td>
                    <td>{{ $cuti->tanggal_selesai }}</td>
                    <td>{{ $cuti->keterangan }}</td>
                    <td>{{ $cuti->pertimbangan_atasan }}</td>
                    <td>{{ $cuti->catatan_atasan }}</td>
                    <td>
                        @if($cuti->pertimbangan_atasan === 'requested')
                            <form method="POST" action="{{ route('cutis.validasi.atasan', $cuti->id) }}">
                                @csrf
                                @method('PUT')
                                <select name="pertimbangan_atasan">
                                    <option value="accepted">Accept</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <input type="text" name="catatan_atasan" required>
                                <button type="submit">Save</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
