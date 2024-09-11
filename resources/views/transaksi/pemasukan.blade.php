@extends('layouts.main')

@section('contents')
@include('layouts.sidebar')
<div id="main">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Tambah Transaksi Pemasukan</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipeTransaksi" value="pemasukan" required>
                    <div class="form-group">
                        <label for="produk">Pilih Produk</label>
                        <select id="produk" name="produk_id" class="choices form-select">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="form-group">
                        <label for="qty">Jumlah (Qty)</label>
                        <input type="number" id="qty" name="qty" class="form-control" min="1" required>
                    </div>
        
                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="text" id="nominal" name="nominal" class="form-control" readonly>
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
        
                    <button type="submit" class="btn btn-primary">Tambah Pemasukan</button>
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
    $(document).ready(function() {
        $('#produk').change(function() {
            const productId = $(this).val();
            const qty = $('#qty').val();

            if (productId) {
                $.ajax({
                    url: '{{ route("product.getPrice") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {
                        if (response.harga) {
                            const harga = response.harga;
                            const qty = $('#qty').val();

                            // Calculate nominal if qty is provided
                            if (qty) {
                                $('#nominal').val(harga * qty);
                            } else {
                                $('#nominal').val('');
                            }

                            // Update the nominal value when qty changes
                            $('#qty').on('input', function() {
                                const updatedQty = $(this).val();
                                $('#nominal').val(harga * updatedQty);
                            });
                        }
                    },
                    error: function() {
                        alert('Error fetching product price.');
                    }
                });
            } else {
                $('#nominal').val('');
            }
        });
    });
</script>

@endsection
