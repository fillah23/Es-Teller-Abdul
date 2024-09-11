<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $month }}</title>
</head>
<body>
    <h3>Laporan Transaksi Bulan {{ $month }}</h3>

    <!-- Display tables side by side using table and colspan -->
    <table>
        <thead>
            <tr>
                <th colspan="6">Pemasukan</th>
                <th></th>
                <th colspan="4">Pengeluaran</th>
            </tr>
            <tr>
                <!-- Pemasukan Headers -->
                <th>ID</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <!-- Empty Spacer -->
                <th></th>
                <!-- Pengeluaran Headers -->
                <th>ID</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Iterate through both Pemasukan and Pengeluaran -->
            @php
                $maxRows = max(count($pemasukan), count($pengeluaran));
            @endphp

            @for ($i = 0; $i < $maxRows; $i++)
            <tr>
                <!-- Pemasukan Data -->
                <td>{{ $i < count($pemasukan) ? $i + 1 : '' }}</td>
                <td>{{ $i < count($pemasukan) ? $pemasukan[$i]->tanggal : '' }}</td>
                <td>{{ $i < count($pemasukan) ? 'Rp ' . number_format($pemasukan[$i]->nominal, 0, ',', '.') : '' }}</td>
                <td>{{ $i < count($pemasukan) ? ($pemasukan[$i]->produk ? $pemasukan[$i]->produk->nama : '-') : '' }}</td>
                <td>{{ $i < count($pemasukan) ? ($pemasukan[$i]->produk ? 'Rp ' . number_format($pemasukan[$i]->produk->harga, 0, ',', '.') : '-') : '' }}</td>
                <td>{{ $i < count($pemasukan) ? $pemasukan[$i]->qty : '' }}</td>

                <!-- Empty Spacer -->
                <td></td>

                <!-- Pengeluaran Data -->
                <td>{{ $i < count($pengeluaran) ? $i + 1 : '' }}</td>
                <td>{{ $i < count($pengeluaran) ? $pengeluaran[$i]->tanggal : '' }}</td>
                <td>{{ $i < count($pengeluaran) ? 'Rp ' . number_format($pengeluaran[$i]->nominal, 0, ',', '.') : '' }}</td>
                <td>{{ $i < count($pengeluaran) ? $pengeluaran[$i]->keterangan : '' }}</td>
            </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <!-- Total Pemasukan -->
                <th colspan="2">Total Pemasukan</th>
                <th>{{ 'Rp ' . number_format($totalPemasukan, 0, ',', '.') }}</th>
                <th colspan="3"></th>
                <th></th>
                <!-- Total Pengeluaran -->
                <th colspan="2">Total Pengeluaran</th>
                <th>{{ 'Rp ' . number_format($totalPengeluaran, 0, ',', '.') }}</th>
                <th></th>
            </tr>
            <tr>
                <!-- Pendapatan Bersih -->
                <th colspan="6">Pendapatan Bersih</th>
                <th></th>
                <th colspan="4">{{ 'Rp ' . number_format($pendapatanBersih, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
