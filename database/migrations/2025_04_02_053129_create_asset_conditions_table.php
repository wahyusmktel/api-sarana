<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique()->comment('Baik, Rusak Ringan, Rusak Berat, dll');
            $table->text('description')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_conditions');
    }
};
