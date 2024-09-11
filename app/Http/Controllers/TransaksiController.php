<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function createPemasukan()
    {
        $products = Product::all();
        return view('transaksi.pemasukan', compact('products'));
    }

    public function createPengeluaran()
    {
        return view('transaksi.pengeluaran');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tipeTransaksi' => 'required|string',
            'shift' => 'required|string',
            'nominal' => 'required|integer',
        ]);

        if ($request->tipeTransaksi === 'pemasukan') {
            $request->validate([
                'produk_id' => 'required|exists:produk,id',
                'qty' => 'required|integer|min:1',
            ]);

            // Hitung nominal secara otomatis berdasarkan harga produk * qty
            $produk = Product::find($request->produk_id);
            $nominal = $produk->harga * $request->qty;
        } else {
            $nominal = $request->nominal;
        }

        Transaksi::create([
            'tanggal' => $request->tanggal,
            'produk_id' => $request->produk_id,
            'qty' => $request->qty,
            'nominal' => $nominal,
            'keterangan' => $request->keterangan,
            'tipeTransaksi' => $request->tipeTransaksi,
            'shift' => $request->shift,
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
    }
    // Controller method to get the product price
    public function getProductPrice(Request $request)
    {
        $product = Product::find($request->product_id);

        if ($product) {
            return response()->json(['harga' => $product->harga]);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }

}
