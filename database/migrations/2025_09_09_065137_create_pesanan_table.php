<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 20)->unique();

            // relasi ke pelanggan
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->foreign('id_pelanggan')
                ->references('id')->on('pelanggan')
                ->nullOnUpdate()
                ->nullOnDelete();

            // relasi ke paket
            $table->unsignedBigInteger('id_paket')->nullable();
            $table->foreign('id_paket')
                ->references('id')->on('paket')
                ->nullOnUpdate()
                ->nullOnDelete();


            $table->decimal('berat', 5, 2)->nullable();
            $table->decimal('harga', 10, 2)->nullable();

            $table->enum('status', [
                'diproses',
                'selesai',
                'dibatalkan'
            ])->default('diproses');

            $table->enum('status_pembayaran', [
                'belum_dibayar',
                'sudah_dibayar'
            ])->default('belum_dibayar');

            $table->dateTime('waktu')->useCurrent();

            $table->decimal('harga_custom', 10, 2)->default(0.00);

            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();

            // $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggan')->nullOnDelete();
            // $table->foreignId('id_paket')->nullable()->constrained('paket')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
