<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat</title>
</head>
<body>
    <h1>List Data Surat</h1>

    <a href="{{ url('/surat/create') }}">Buat Surat</a>
    <a href="{{ url('/disposisi') }}">Lihat Disposisi</a>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Pengirim</th>
                <th>Tanggal Surat</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Nomor Agenda</th>
                <th>Tanggal Terima</th>
                <th>Sifat Surat</th>
                <th>File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
                <tr>
                    <td>{{ $d->pengirim }}</td>
                    <td>{{ $d->tanggal_surat }}</td>
                    <td>{{ $d->no_surat }}</td>
                    <td>{{ $d->perihal }}</td>
                    <td>{{ $d->nomor_agenda }}</td>
                    <td>{{ $d->tanggal_terima }}</td>
                    <td>{{ $d->sifat_surat }}</td>
                    <td>
                        @if($d->file_path !== '-')
                            <a href="{{ asset($d->file_path) }}" target="_blank">{{ $d->file_path }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
