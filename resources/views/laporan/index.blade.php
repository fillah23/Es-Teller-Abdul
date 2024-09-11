@extends('layouts.main')

@section('contents')
@include('layouts.sidebar')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Laporan Transaksi</h3>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Transaksi</h5>
                    <a href="{{ route('laporan.export-excel', ['date_range' => request('date_range')]) }}" class="btn btn-success">Export Excel</a>
                </div>
                <div class="card-body">
                    <!-- Form Filter -->
                    <form id="filter-form" action="{{ route('laporan.index') }}" method="GET">
                        <div class="row align-items-end">
                            <!-- Filter Tipe Transaksi -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tipeTransaksi">Tipe Transaksi</label>
                                    <select class="form-select" name="tipeTransaksi" id="tipeTransaksi">
                                        <option value="">Semua</option>
                                        <option value="pemasukan" {{ request('tipeTransaksi') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                        <option value="pengeluaran" {{ request('tipeTransaksi') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Shift -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shift">Shift</label>
                                    <select class="form-select" name="shift" id="shift">
                                        <option value="">Semua Shift</option>
                                        <option value="pagi" {{ request('shift') == 'Pagi' ? 'selected' : '' }}>Pagi</option>
                                        <option value="sore" {{ request('shift') == 'Sore' ? 'selected' : '' }}>Sore</option>
                                        <option value="Diluar Shift" {{ request('shift') == 'Diluar Shift' ? 'selected' : '' }}>Diluar Shift</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Rentang Tanggal -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_range">Rentang Tanggal</label>
                                    <input type="text" class="form-control flatpickr-range" name="date_range" placeholder="Select date.." value="{{ request('date_range', \Carbon\Carbon::now()->format('Y-m-d') . ' to ' . \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Display Totals -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <h6>Total Pemasukan: Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-md-4">
                            <h6>Total Pengeluaran: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h6>
                        </div>
                        <div class="col-md-4">
                            <h6>Laba Bersih: Rp {{ number_format($labaBersih, 0, ',', '.') }}</h6>
                        </div>
                    </div>

                    <!-- Table Data Transaksi -->
                    <table class="table table-striped mt-3" id="table1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                @if(request('tipeTransaksi') == 'pemasukan')
                                    <th>Nama Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>Qty</th>
                                @endif
                                @if(request('tipeTransaksi') == 'pengeluaran')
                                    <th>Keterangan</th>
                                @endif
                                <th>Shift</th>
                                <th>Tipe Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                @if(request('tipeTransaksi') == 'pemasukan')
                                    <td>{{ $item->produk ? $item->produk->nama : '-' }}</td>
                                    <td>{{ $item->produk ? number_format($item->produk->harga, 0, ',', '.') : '-' }}</td>
                                    <td>{{ $item->qty }}</td>
                                @endif
                                @if(request('tipeTransaksi') == 'pengeluaran')
                                    <td>{{ $item->keterangan }}</td>
                                @endif
                                <td>{{ $item->shift }}</td>
                                <td>{{ $item->tipeTransaksi }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    @include('layouts.footer')
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi flatpickr untuk rentang tanggal
        flatpickr(".flatpickr-range", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: @if(request('date_range'))
                "{{ request('date_range') }}".split(' to ')
            @else
                ["{{ \Carbon\Carbon::now()->format('Y-m-d') }}", "{{ \Carbon\Carbon::now()->format('Y-m-d') }}"]
            @endif,
            onClose: function(selectedDates, dateStr, instance) {
                document.getElementById('filter-form').submit(); // Submit form setelah pilih tanggal
            }
        });

        // Submit form ketika filter tipe transaksi berubah
        document.getElementById('tipeTransaksi').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });

        // Submit form ketika filter shift berubah
        document.getElementById('shift').addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endsection
