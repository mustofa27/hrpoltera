<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Disposisi</title>
</head>
<body>
    <h1>Form Pengiriman Disposisi</h1>
    <a href="{{ url('/disposisi') }}">Kembali</a>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('create-disposisi') }}">
        @csrf

        <label>Tanggal Disposisi</label>
        <input type="text" name="tanggal_disposisi" value="{{ old('tanggal_disposisi') }}" required>

        <label>Surat</label>
        <select name="surat" required>
            @foreach($data['surat'] as $d)
                <option value="{{ $d->id }}">{{ $d->pengirim }} - {{ $d->no_surat }}</option>
            @endforeach
        </select>

        <label>User Tujuan</label>
        <select name="user" required>
            @foreach($data['user'] as $d)
                <option value="{{ $d->id }}">{{ $d->nama }}</option>
            @endforeach
        </select>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
