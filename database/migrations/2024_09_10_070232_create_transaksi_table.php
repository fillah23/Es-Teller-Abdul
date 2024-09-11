<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal'); // Kolom untuk tanggal transaksi
            $table->foreignId('produk_id')->nullable()->constrained('produk')->onDelete('cascade'); // Relasi dengan produk (nullable)
            $table->integer('qty')->nullable(); // Kuantitas bisa nullable
            $table->bigInteger('nominal'); // Nominal transaksi
            $table->text('keterangan')->nullable(); // Keterangan transaksi
            $table->string('tipeTransaksi', 20);
            $table->string('shift', 20);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Relasi dengan tabel users
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
