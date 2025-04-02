<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('room_code')->nullable()->comment('Kode ruang atau lokasi');
            $table->string('building_name')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('description')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_locations');
    }
};
