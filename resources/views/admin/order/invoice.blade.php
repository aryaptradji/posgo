<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $order->code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #000;
            padding: 20px;
        }

        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 120px;
            height: auto;
            margin-bottom: 10px;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-meta {
            margin-bottom: 20px;
            text-align: right;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-box {
            width: 48%;
        }

        .info-box h4 {
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .total-section {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .footer {
            font-size: 10px;
            color: #555;
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

    <div class="no-print" style="text-align: right; margin-bottom: 10px;">
        <button onclick="window.print()">Print Invoice</button>
    </div>

    <div class="header">
        <img src="{{ asset('img/posgo-logo.png') }}" alt="Logo">
        <div class="invoice-title">INVOICE</div>
    </div>

    <div class="invoice-meta">
        <div>Invoice #: {{ $order->code }}</div>
        <div>Tanggal: {{ $order->time->translatedFormat('d M Y H:i') }}</div>
        <div>Status: <strong style="color: green;">LUNAS</strong></div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h4>Informasi Customer</h4>
            <div>Nama: {{ $order->user->name }}</div>
            <div>Email: {{ $order->user->email ?? '-' }}</div>
            <div>No. HP: {{ $order->user->phone ?? '-' }}</div>
        </div>
        <div class="info-box">
            <h4>Informasi Pembayaran</h4>
            <div>Metode: {{ $order->payment_method }}</div>
            <div>Waktu Pembayaran: {{ $order->time->translatedFormat('d M Y H:i') }}</div>
            <div>Kategori: {{ $order->category }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Pcs</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->pcs }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        TOTAL: Rp {{ number_format($order->total, 0, ',', '.') }}
    </div>

    <div class="footer">
        Terima kasih atas pesanan Anda.<br>
        Ini adalah bukti pembayaran yang sah.
    </div>

</body>

</html>
