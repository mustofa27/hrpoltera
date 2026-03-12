<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aktivitas Lampau</title>
</head>
<body>
    <h1>Tambah Aktivitas Tanggal Lampau</h1>

    <form method="POST" action="{{ url('/aktivitas/store_past_date') }}" enctype="multipart/form-data">
        @csrf
        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <label>Jam (HH:MM:SS)</label>
        <input type="text" name="jam" placeholder="08:00:00" required>

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
</body>
</html>
