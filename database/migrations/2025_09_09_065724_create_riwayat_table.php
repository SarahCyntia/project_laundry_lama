<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id(); // primary key
            $table->unsignedBigInteger('id_pesanan'); // foreign key ke pesanan
            $table->date('tgl_selesai');
            $table->decimal('harga', 10, 2);

            // relasi ke pesanan
            $table->foreign('id_pesanan')
                ->references('id')->on('pesanan')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};
