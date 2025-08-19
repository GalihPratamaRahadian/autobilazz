<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .logo {
            width: 150px;
        }

        .header {
            text-align: center;
            font-size: 15px;
            margin-bottom: 20px;
        }

        .section {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .no-border td {
            border: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ $logo }}" alt="Logo" class="logo">
        <h2>Struk Pembayaran</h2>
    </div>

    <table class="no-border">
        <tr>
            <td><strong>Tanggal Transaksi:</strong></td>
            <td>{{ \Carbon\Carbon::parse($dataPayment->date_transaction)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>No. Plat:</strong></td>
            <td>{{ $dataPayment->order->license_plate }}</td>
        </tr>
        <tr>
            <td><strong>Nama Layanan:</strong></td>
            <td>{{ $dataPayment->service->name }}</td>
        </tr>
        <tr>
            <td><strong>Total:</strong></td>
            <td>Rp {{ number_format($dataPayment->total_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Bayar:</strong></td>
            <td>Rp {{ number_format($dataPayment->paid_off, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Kembalian:</strong></td>
            <td>Rp {{ number_format($dataPayment->payback, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Metode Pembayaran:</strong></td>
            <td>{{ $dataPayment->payment_method }}</td>
        </tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td>{{ $dataPayment->status }}</td>
        </tr>
        <tr>
            <td><strong>Petugas:</strong></td>
            <td>{{ auth()->user()->name }}</td>
        </tr>
    </table>

    <div class="section">
        <p class="text-center">Terima kasih telah menggunakan layanan kami.</p>
    </div>

</body>
</html>
