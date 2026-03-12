<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aktivitas Pribadi</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; padding: 32px; }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #4f46e5; }
        .header h2 { font-size: 16px; font-weight: 700; color: #4f46e5; letter-spacing: 0.05em; text-transform: uppercase; }
        .meta { margin-bottom: 16px; line-height: 1.8; }
        .meta strong { display: inline-block; width: 120px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        thead tr { background: #4f46e5; color: #fff; }
        th { padding: 8px 10px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .status-ok { color: #16a34a; font-weight: 600; }
        .status-no { color: #dc2626; font-weight: 600; }
        .footer { margin-top: 32px; text-align: right; font-size: 11px; color: #64748b; }
        @media print { body { padding: 16px; } }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Aktivitas Harian Pribadi</h2>
    </div>
    <div class="meta">
        <p><strong>Tanggal</strong>: {{ $tanggal }}</p>
        <p><strong>Pegawai</strong>: {{ auth()->user()->nama }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Uraian Pekerjaan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($aktivitas as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->jam }}</td>
                    <td>{{ $item->kegiatan }}</td>
                    <td class="{{ $item->status === 'Selesai' ? 'status-ok' : 'status-no' }}">{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui: {{ $atasan->nama ?? '-' }}</p>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
