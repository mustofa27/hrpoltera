<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>Master {{ $title }}</h1>

    <form method="POST" action="{{ route($storeRoute) }}">
        @csrf
        @foreach($fields as $field)
            <label>{{ $field['label'] }}</label>
            <input
                type="{{ $field['type'] }}"
                name="{{ $field['name'] }}"
                @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                required
            >
        @endforeach
        <button type="submit">Submit</button>
    </form>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                @endforeach
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($columns as $column)
                        <td>{{ $row->{$column} }}</td>
                    @endforeach
                    <td>
                        <form method="POST" action="{{ route($deleteRoute, $row) }}">
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
