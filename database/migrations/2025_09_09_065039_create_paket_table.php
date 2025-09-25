<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paket', function (Blueprint $table) {
            $table->id(); // int auto increment primary key
            $table->string('nama', 50);
            $table->decimal('harga', 10, 2);
            $table->text('keterangan')->nullable();
            $table->string('icon', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};
