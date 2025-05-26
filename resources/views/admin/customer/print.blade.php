<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Daftar Customer</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body onload="window.print()">
    <h2>Daftar Customer</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->phone_number }}</td>
                    <td>
                        {{ $c->address->street ?? '-' }},
                        RT {{ $c->address->neighborhood->rt ?? '-' }}/RW {{ $c->address->neighborhood->rw ?? '-' }},
                        Kel. {{ $c->address->neighborhood->subDistrict->name ?? '-' }},
                        Kec. {{ $c->address->neighborhood->subDistrict->district->name ?? '-' }},
                        {{ $c->address->neighborhood->subDistrict->district->city->name ?? '-' }},
                        {{ $c->address->neighborhood->postal_code ?? '-' }}
                    </td>
                    <td>{{ $c->created->translatedFormat('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
