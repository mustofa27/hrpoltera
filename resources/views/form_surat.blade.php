<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Surat</title>
</head>
<body>
    <h1>Form Surat</h1>
    <a href="{{ url('/surat') }}">Kembali</a>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('create-surat') }}" enctype="multipart/form-data">
        @csrf
        <label>Pengirim</label>
        <input type="text" name="pengirim" value="{{ old('pengirim') }}" required>

        <label>Tanggal Surat</label>
        <input type="text" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required>

        <label>Nomor Surat</label>
        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required>

        <label>Perihal</label>
        <input type="text" name="perihal" value="{{ old('perihal') }}" required>

        <label>Nomor Agenda</label>
        <input type="text" name="nomor_agenda" value="{{ old('nomor_agenda') }}" required>

        <label>Tanggal Terima</label>
        <input type="text" name="tanggal_terima" value="{{ old('tanggal_terima') }}" required>

        <label>Sifat Surat</label>
        <select name="sifat_surat">
            <option value="Penting">Penting</option>
            <option value="Rahasia">Rahasia</option>
            <option value="Segera">Segera</option>
            <option value="Biasa">Biasa</option>
        </select>

        <label>File Surat</label>
        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">

        <button type="submit">Submit</button>
    </form>
</body>
</html>
