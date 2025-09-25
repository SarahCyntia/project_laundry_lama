<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('antarjemput_status', function (Blueprint $table) {
            $table->id(); // int auto increment primary key
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('updated_at')->useCurrent();
            $table->string('updated_by', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antarjemput_status');
    }
};
