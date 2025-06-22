<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice PO - {{ $po->code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Gaya khusus untuk cetak */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20mm;
            font-size: 10pt;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header .title {
            margin-top: -30px;
        }

        .logo {
            width: 150px;
            height: auto;
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
            margin-top: 50px;
        }

        .no-print button:hover {
            transform: scale(115%);
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }

        .no-print button:active {
            transform: scale(90%);
            box-shadow: none;
        }

        .header h1 {
            margin: 0;
            font-size: 20pt;
            color: #222;
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

        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .table-items th,
        .table-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table-items th {
            background-color: #f2f2f2;
            font-weight: bold;
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

        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            /* Ruang untuk tanda tangan */
            padding-bottom: 5px;
        }

        td.text-right, th.text-right {
            text-align: right;
        }

        /* Hanya untuk media cetak */
        @media print {
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }

            body {
                font-size: 10pt;
                padding: 0;
            }

            .container {
                width: 100%;
                max-width: none;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            table,
            pre,
            blockquote,
            img,
            svg {
                break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/posgo-logo.png') }}" alt="Logo" class="logo">
            <div class="title">
                <h1>INVOICE PURCHASE ORDER</h1>
                <p><strong>Nomor PO: {{ $po->code }}</strong></p>
                <p>Tanggal Dibuat: {{ $po->created->translatedFormat('d M Y H:i:s') }}</p>
            </div>
        </div>

        <hr style="border: 0; border-top: 2px solid #ccc; margin: 30px 0;">

        <div class="info-section">
            <div class="info-column">
                <h2>Informasi Supplier</h2>
                <p>Nama: {{ $po->supplier->name }}</p>
                <p>Alamat: {{ $po->supplier->address ?? '-' }}</p> {{-- Tambahkan kolom alamat jika ada --}}
                <p>Telepon: {{ $po->supplier->phone ?? '-' }}</p> {{-- Tambahkan kolom telepon jika ada --}}
                <p>Email: {{ $po->supplier->email ?? '-' }}</p> {{-- Tambahkan kolom telepon jika ada --}}
            </div>
            <div class="info-column">
                <h2>Informasi Pembeli</h2>
                <p>Nama Perusahaan: Toko Biyan</p> {{-- Ganti dengan nama perusahaan Anda --}}
                <p>Alamat: Jl. Inpres Raya No.2, RT.004/RW.004, Gaga, Kec. Larangan, Kota Tangerang, Banten 15154</p>
                {{-- Ganti dengan alamat perusahaan Anda --}}
                <p>Telepon: 085100270185</p> {{-- Ganti dengan telepon perusahaan Anda --}}
            </div>
        </div>

        <h2>Daftar Produk</h2>
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 40%;">Nama Produk</th>
                    <th class="text-right" style="width: 10%;" class="text-right">PCS</th>
                    <th class="text-right" style="width: 10%;" class="text-right">QTY</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($po->items as $index => $item)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-right">{{ $item->pcs }}</td>
                        <td class="text-right">{{ $item->qty }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Tidak ada produk dalam pesanan ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="table-summary">
            <tr class="total-row">
                <td>Total QTY:</td>
                <td>{{ $po->items->sum('qty') }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Item:</td>
                <td>{{ $po->item }}</td>
            </tr>
        </table>

        <div style="clear: both;"></div> {{-- Mengatasi float --}}

        <div class="signature-section">
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="signature-line"></div>
                <p>({{ $po->supplier->name }})</p> {{-- Atau siapa yang menandatangani dari sisi Anda --}}
            </div>
            <div class="signature-box">
                <p>Diterima Oleh,</p>
                <div class="signature-line"></div>
                <p>(_________________________)</p>
            </div>
        </div>

        <div class="footer no-print">
            <button onclick="window.print()">Cetak Invoice</button>
        </div>
    </div>
</body>

</html>
