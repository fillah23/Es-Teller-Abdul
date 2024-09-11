<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = [
        'tanggal',
        'produk_id',
        'qty',
        'nominal',
        'keterangan',
        'tipeTransaksi',  // "pemasukan" atau "pengeluaran"
        'shift',
        'user_id',
    ];

    // Relasi dengan produk (banyak transaksi dapat memiliki satu produk)
    public function produk()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    // Relasi dengan user (banyak transaksi dilakukan oleh satu user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
