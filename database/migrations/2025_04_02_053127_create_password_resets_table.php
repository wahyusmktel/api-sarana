<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->useCurrent();

            // Tidak pakai FK karena email bisa saja tidak valid (dari user nonaktif misalnya)
        });
    }

    public function down(): void {
        Schema::dropIfExists('password_resets');
    }
};
