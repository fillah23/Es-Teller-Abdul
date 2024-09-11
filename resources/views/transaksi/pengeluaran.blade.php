@extends('layouts.main')

@section('contents')
@include('layouts.sidebar')
<div id="main">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Tambah Transaksi Pengeluaran</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipeTransaksi" value="pengeluaran">
        
                    <div class="form-group">
                        <label for="nominal">Nominal Pengeluaran</label>
                        <input type="number" id="nominal" name="nominal" class="form-control" required>
                    </div>
        
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                    </div>
                    
                    <fieldset class="form-group">
                        <label for="shift">shift</label>
                        <select class="form-select" id="shift" name="shift" required>
                            <option>Pagi</option>
                            <option>Sore</option>
                            <option>Diluar Shift</option>
                        </select>
                    </fieldset>

                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Tambah Pengeluaran</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
        });
        @endif
        @if ($errors->any())
        Swal.fire({
            title: 'Kesalahan!',
            html: `
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            `,
            icon: 'error',
            confirmButtonText: 'OK'
        });
        @endif
</script>
@endsection
