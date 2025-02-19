<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 2px 0;
            color: #555;
            font-size: 14px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
            color: #555;
        }
        .info strong {
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background: #f4f4f4;
            color: #333;
        }
        .table td {
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- header -->
        <div class="header">
            <h1>SD SANTO MIKAEL</h1>
            <p>Jl. Inspeksi Kali Sunter No.13, Sumur Batu, Kec. Kemayoran</p>
            <p>Kota Jakarta Pusat 10640</p>
            <p>Telp: (021) 4204914</p>
            <h2 style="margin-top: 20px;">BUKTI PEMBAYARAN</h2>
        </div>

        <!-- informasi siswa -->
        <div class="info">
            <p><strong>NIS:</strong> {{ $student->nis }}</p>
            <p><strong>Nama:</strong> {{ $student->name }}</p>
            <p><strong>Kelas:</strong> {{ $kelas->name }}</p>
            <p><strong>Tanggal Pembayaran:</strong> {{ \Carbon\Carbon::parse($transaksi->updated_at)->format('d M Y') }}</p>
            <p><strong>Tahun Pelajaran:</strong> {{ $periode->name}}</p>
        </div>

        <!-- rincian pembayaran -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pembayaran</th>
                    <th>Total Tagihan</th>
                    <th>Jumlah Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $pembayaran['name'] }}</td>
                    <td>Rp{{ number_format($pembayaran['amount'], 2, ',', '.') }}</td>
                    <td>Rp{{ number_format($pembayaran['amount'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong>Rp{{ number_format($totalAmount, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- footer -->
        <div class="footer">
            <p>Terima kasih telah melakukan pembayaran!</p>
        </div>
    </div>
</body>
</html>
