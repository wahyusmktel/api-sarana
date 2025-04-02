<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique()->comment('Jenis aset: Laptop, Proyektor, Meja, dll');
            $table->uuid('category_id');

            $table->foreign('category_id')->references('id')->on('asset_categories')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_types');
    }
};
