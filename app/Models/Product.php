<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $fillable = ['nama', 'harga'];
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
