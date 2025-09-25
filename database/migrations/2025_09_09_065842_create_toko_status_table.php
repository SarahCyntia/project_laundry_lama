<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('toko_status', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->dateTime('waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toko_status');
    }
};
