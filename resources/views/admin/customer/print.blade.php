<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Daftar Customer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
            font-size: 12px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 20px;
        }

        .header span {
            font-weight: 500;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .no-print {
            font-family: 'Poppins', sans-serif !important;
            display: block;
            margin-bottom: 20px;
            text-align: right;
            padding: 20px;
        }

        .no-print button {
            font-family: 'Poppins' !important;
            font-weight: bold;
            border: none;
            color: white;
            background: linear-gradient(to right, #E4763F, #7A24F9);
            padding: 6px 12px;
            border-radius: 25px;
            transition: all 200ms ease;
        }

        .no-print button:hover {
            transform: scale(115%);
            box-shadow: 0px 4px 4px 0px rgba(0,0,0,0.25);
        }

        .no-print button:active {
            transform: scale(90%);
            box-shadow: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()">Print</button>
    </div>

    <div class="header">
        <img src="{{ asset('img/posgo-logo.png') }}" alt="Logo" class="logo">
        <h1 class="title">Daftar Customer</h1>
        <span>{{ now() }}</span>
    </div>

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
