<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #222;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #ddd;
        }
        h2, h4 {
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
        }
        .info {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Omset Auto Bilazz</h2>
        <h4>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h4>
    </div>

    <div class="info">
        <p>Total Data: {{ $payments->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Tanggal Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Layanan Yang Diambil</th>
                <th>Jenis Kendaraan</th>
                <th>Plat Nomor</th>
                <th>Harga Total Paket</th>
                <th>Uang Yang Dibayarkan</th>
                <th>Kembalian</th>
                <th>Metode Pembayaran</th>
                <th>Status Pembayaran</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->date_transaction ?? '-' }}</td>
                    <td>{{ $payment->order->name ?? '-' }}</td>
                    <td>{{ $payment->service->name ?? '-' }}</td>
                    <td>{{ $payment->order->vehicle_type ?? '-' }}</td>
                    <td>{{ $payment->order->license_plate ?? '-' }}</td>
                    <td>Rp {{ number_format($payment->total_price ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($payment->paid_off ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($payment->payback ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $payment->payment_method ?? '-' }}</td>
                    <td>{{ $payment->status ?? '-' }}</td>
                    <td>{{ auth()->user()->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center">Tidak ada data</td>
                </tr>
                @endforelse
                <tr>
                    <td>Total Harga Keseluruhan</td>
                    <td colspan="11" class="text-center">Rp {{ number_format($payments->sum('total_price'), 0, ',', '.') }}</td>
                </tr>
        </tbody>
    </table>

</body>
</html>
