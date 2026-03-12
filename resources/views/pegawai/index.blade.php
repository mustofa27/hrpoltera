<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai</title>
</head>
<body>
    <h1>Data Pegawai</h1>

    <form method="POST" action="{{ route('pegawais.store') }}">
        @csrf
        <label>User</label>
        <select name="user_id" required>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->email }})</option>
            @endforeach
        </select>

        <label>NIP</label>
        <input type="text" name="nip" required>

        <label>Shift</label>
        <select name="shift_id" required>
            @foreach($shifts as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
        </select>

        <label>Atasan Langsung</label>
        <select name="atasan_langsung_id" required>
            @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->nama }}</option>
            @endforeach
        </select>

        <label>Gelar Depan</label>
        <input type="text" name="gelar_depan">

        <label>Gelar Belakang</label>
        <input type="text" name="gelar_belakang">

        <label>Urutan</label>
        <input type="number" name="urutan" required min="1">

        <button type="submit">Simpan</button>
    </form>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Gelar</th>
                <th>Atasan</th>
                <th>Shift</th>
                <th>Urutan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $p)
                <tr>
                    <td>{{ $p->user->nama ?? '-' }}</td>
                    <td>{{ $p->nip }}</td>
                    <td>{{ trim(($p->gelar_depan ?? '').' '.($p->gelar_belakang ?? '')) }}</td>
                    <td>{{ $p->atasan->nama ?? '-' }}</td>
                    <td>{{ $p->shift->nama ?? '-' }}</td>
                    <td>{{ $p->urutan }}</td>
                    <td>
                        <form method="POST" action="{{ route('pegawais.destroy', $p) }}">
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
