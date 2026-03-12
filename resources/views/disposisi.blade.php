<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposisi</title>
</head>
<body>
    <h1>List Data Disposisi</h1>

    <a href="{{ url('/disposisi/create') }}">Kirim Disposisi</a>
    <a href="{{ url('/surat') }}">Lihat Surat</a>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Isi Disposisi</th>
                <th>Tanggal Disposisi</th>
                <th>Tanggal Terima</th>
                <th>Catatan Tindak Lanjut</th>
                <th>Pengirim Surat</th>
                <th>File Surat</th>
                <th>User Asal</th>
                <th>User Tujuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
                <tr>
                    <td>{{ $d->isi_disposisi }}</td>
                    <td>{{ $d->tanggal_disposisi }}</td>
                    <td>{{ $d->tanggal_diterima }}</td>
                    <td>{{ $d->catatan_tindak_lanjut }}</td>
                    <td>{{ $d->surat }}</td>
                    <td>
                        @if($d->file !== '-')
                            <a href="{{ asset($d->file) }}" target="_blank">{{ $d->file }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $d->userasal }}</td>
                    <td>{{ $d->user }}</td>
                    <td><a href="{{ route('disposisi.destroy', $d->id) }}">Delete</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
