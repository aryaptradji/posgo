<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Delivery Note - {{ $delivery->code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logo {
            margin-left: -24px;
            width: 120px;
            height: auto;
        }

        .sectionA {
            text-transform: capitalize;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 2px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 10px;
            font-size: 10px;
            color: #555;
            display: flex;
            justify-content: space-between;
        }

        .signature {
            margin-top: 60px;
            text-align: center;
        }

        .no-print {
            margin-bottom: 20px;
            text-align: right;
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

        @media print {
            .no-print {
                display: none !important;
            }

            .container {
                margin-top: -40px;
            }

            @page {
                size: A6 portrait;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()">Print</button>
    </div>

    <div class="container">
        <div class="header">
            <img src="{{ asset('img/posgo-logo.png') }}" alt="Logo" class="logo">
            <div class="title">SURAT JALAN</div>
            <div>{{ now()->translatedFormat('d M Y') }}</div>
        </div>

        <div class="sectionA">
            <strong>No Pengiriman: </strong> {{ $delivery->code }}<br>
            <strong>Tanggal Pengiriman: </strong> {{ $delivery->shipped_at_formatted }}<br>
            <strong>Pelanggan: </strong> {{ $delivery->user->name }}<br>
            <strong>Kurir: </strong> {{ $delivery->courier->name ?? '-' }}<br>
            <strong>Status Pengiriman: </strong> {{ $delivery->shipping_status }}<br>
        </div>

        <div class="sectionB">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Pcs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($delivery->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->product->pcs }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div class="signature">
                <div>Kurir</div>
                <div style="margin-top: 60px;">__________________________</div>
            </div>
            <div class="signature">
                <div>Penerima</div>
                <div style="margin-top: 60px;">__________________________</div>
            </div>
        </div>
    </div>

</body>

</html>
