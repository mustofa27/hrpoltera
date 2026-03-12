<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Lupa Absen</title>
</head>
<body>
    <h1>Pengajuan Lupa Absen</h1>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @elseif(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('lupa_absens.store') }}">
        @csrf
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <label>Jam Masuk (HH:MM)</label>
        <input type="time" name="jam_masuk" required>

        <label>Jam Pulang (HH:MM)</label>
        <input type="time" name="jam_pulang" required>

        <label>Aktivitas</label>
        <input type="text" name="aktivitas" required>

        <button type="submit">Ajukan</button>
    </form>

    <h2>Riwayat Pengajuan</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Aktivitas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
                <tr>
                    <td>{{ $d->tanggal_lupa }}</td>
                    <td>{{ $d->jam_masuk }}</td>
                    <td>{{ $d->jam_pulang }}</td>
                    <td>{{ $d->aktivitas }}</td>
                    <td>{{ $d->status }}</td>
                    <td>
                        @if($d->status === 'requested')
                            <form method="POST" action="{{ route('lupa_absens.destroy', $d->id) }}">
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
