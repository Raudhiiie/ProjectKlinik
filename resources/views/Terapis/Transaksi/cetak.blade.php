<!DOCTYPE html>
<html>

    <head>
        <title>Cetak Transaksi #{{ $transaksi->id }}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
            }

            .info {
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            table,
            th,
            td {
                border: 1px solid #ddd;
            }

            th,
            td {
                padding: 8px;
                text-align: left;
            }

            .total {
                font-weight: bold;
                text-align: right;
                font-size: 1.2em;
            }

            .footer {
                margin-top: 30px;
                padding-top: 10px;
                border-top: 1px dashed #333;
                text-align: center;
            }

            @media print {
                .no-print {
                    display: none;
                }

                body {
                    padding: 0;
                }
            }
        </style>
    </head>

    <body>
        <div class="header">
            <h2>KLINIK TERAPI</h2>
            <h3>Struk Transaksi</h3>
            <p>No. Transaksi: {{ $transaksi->id }}</p>
        </div>

        <div class="info">
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y H:i') }}</p>
            <p><strong>Pasien:</strong> {{ $transaksi->pasien->nama }} ({{ $transaksi->no_rm }})</p>
            <p><strong>Terapis:</strong> {{ $transaksi->terapis->nama ?? '-' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($detail->jenis == 'layanan')
                                {{ $detail->subLayanan->layanan->nama ?? 'Layanan dihapus' }} -
                                {{ $detail->subLayanan->nama ?? '' }}
                            @else
                                {{ $detail->produk->nama_produk ?? 'Produk dihapus' }}
                            @endif
                        </td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p>Total: Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
            <p>Metode Pembayaran: {{ ucfirst($transaksi->metode_pembayaran) }}</p>
            <p>Status: {{ $transaksi->keterangan }}</p>
            @if($transaksi->keterangan == 'Lunas')
                <p>Tanggal Bayar: {{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d/m/Y H:i') }}</p>
            @endif

        </div>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda</p>
            <p>Alamat Klinik: Jl. Contoh No. 123, Kota Anda</p>
            <p>Telp: (021) 12345678</p>
            <button onclick="window.print()" class="no-print">Cetak Struk</button>
        </div>

        <script>
            window.onload = function () {
                // window.print(); // Uncomment untuk auto print
            };
        </script>
    </body>

</html>