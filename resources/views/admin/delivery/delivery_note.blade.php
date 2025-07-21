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

        th,
        td {
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

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-column {
            width: 48%;
        }

        .info-column h2 {
            font-size: 12pt;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            color: #555;
        }

        .table-summary {
            width: 300px;
            /* Lebar untuk tabel ringkasan */
            float: right;
            /* Posisikan di kanan */
            margin-bottom: 30px;
            border-collapse: collapse;
            border-top: 1px solid #ddd;
        }

        .table-summary td {
            padding: 5px 10px;
            border: none;
            text-align: right;
        }

        .table-summary .total-row {
            font-weight: bold;
            padding-top: 10px;
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
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
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
            <strong>Kurir: </strong> {{ $delivery->courier->name ?? '-' }}<br>
        </div>

        <div class="info-section">
            <div class="info-column">
                <h2>Informasi Penerima</h2>
                <p>Nama: {{ $delivery->user->name }}</p>
                <p>
                    Alamat: {{ $delivery->user->address->street ?? '-' }},
                    RT {{ $delivery->user->address->neighborhood->rt ?? '-' }}/RW
                    {{ $delivery->user->address->neighborhood->rw ?? '-' }},
                    Kec. {{ $delivery->user->address->neighborhood->subDistrict->district->name ?? '-' }},
                    Kel. {{ $delivery->user->address->neighborhood->subDistrict->name ?? '-' }},
                    {{ $delivery->user->address->neighborhood->subDistrict->district->city->name ?? '-' }}
                </p> {{-- Tambahkan kolom alamat jika ada --}}
                <p>Telepon: {{ $delivery->user->phone_number ?? '-' }}</p> {{-- Tambahkan kolom telepon jika ada --}}
                <p>Email: {{ $delivery->user->email ?? '-' }}</p> {{-- Tambahkan kolom telepon jika ada --}}
            </div>
            <div class="info-column">
                <h2>Informasi Pengirim</h2>
                <p>Nama Perusahaan: Toko Biyan</p> {{-- Ganti dengan nama perusahaan Anda --}}
                <p>Alamat: Jl. Inpres Raya No.2, RT.004/RW.004, Gaga, Kec. Larangan, Kota Tangerang, Banten 15154</p>
                {{-- Ganti dengan alamat perusahaan Anda --}}
                <p>Telepon: 085100270185</p> {{-- Ganti dengan telepon perusahaan Anda --}}
            </div>
        </div>

        <div class="sectionB">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Pcs</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($delivery->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->product->pcs }}</td>
                            <td>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table-summary">
                <tr class="total-row">
                    <td>Total</td>
                    <td>Rp {{ number_format($delivery->total, 0, ',', '.') }}</td>
                </tr>
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
