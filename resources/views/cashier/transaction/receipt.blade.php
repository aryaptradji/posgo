<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .receipt {
            width: 72mm;
            margin: auto;
            padding: 5mm;
        }

        .justify-between {
            display: flex;
            justify-content: space-between;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .no-print {
            font-family: 'Poppins', sans-serif !important;
            text-align: center;
            display: block;
            margin-bottom: 20px;
            text-align: center;
            padding: 20px;
        }

        .no-print button,a {
            font-family: 'Poppins' !important;
            font-weight: bold;
            border: none;
            color: white;
            background: linear-gradient(to right, #E4763F, #7A24F9);
            padding: 6px 12px;
            border-radius: 25px;
            transition: all 200ms ease;
        }

        .no-print button,a:hover {
            transform: scale(115%);
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
        }

        .no-print button,a:active {
            transform: scale(90%);
            box-shadow: none;
        }

        .no-print .back {
            background: none;
            color: black;
            text-decoration: none;
            background-color: #e5e7eb;
            margin-right: 20px;
        }

        .capitalize {
            text-transform: capitalize;
        }

        .logo {
            width: 100px;
        }

        .time {
            margin-top: -24px;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="receipt">
        <div class="center">
            <img src="{{ asset('img/posgo-logo.png') }}" alt="Logo" class="logo">
        </div>
        <div class="center mt-1 time">
            {{ now()->format('d/m/Y H:i') }}
        </div>
        <hr>

        <div class="mt-1">
            No. Pesanan: {{ $order->code }}<br>
            Nama: {{ $order->user->name }}<br>
            <span class="capitalize">Metode: {{ $order->payment_method }}</span>
        </div>

        <hr>
        @foreach ($order->items as $item)
            <div>
                <div>{{ $item->product->name }} ({{ $item->product->pcs }} pcs)</div>
                <div class="justify-between">
                    <span>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
        <hr>

        <div>
            <div class="justify-between bold">
                <span>Total</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
            <div class="justify-between">
                <span>Bayar</span>
                <span>Rp {{ number_format($order->paid, 0, ',', '.') }}</span>
            </div>
            <div class="justify-between">
                <span>Kembalian</span>
                <span>Rp {{ number_format($order->change, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="center mt-2">
            ~ Terima Kasih ~
        </div>
    </div>

    <div class="no-print">
        <a href="{{ route('pos-menu') }}" class="back">Kembali</a>
        <button onclick="window.print()">Print</button>
    </div>
</body>

</html>
