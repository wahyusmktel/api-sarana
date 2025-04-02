<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->string('serial_number')->nullable()->comment('Nomor seri unik dari masing-masing unit');

            $table->uuid('category_id');
            $table->uuid('type_id')->nullable();
            $table->uuid('condition_id')->nullable();
            $table->uuid('location_id')->nullable();
            $table->uuid('responsible_user_id')->nullable()->comment('Penanggung jawab aset');

            $table->date('acquisition_date')->nullable();
            $table->double('acquisition_cost')->nullable();
            $table->string('funding_source')->nullable();
            $table->boolean('is_active')->default(true);

            $table->string('qr_code_path')->nullable()->comment('Path atau URL ke QR code yang digenerate');

            $table->timestamps();

            // Foreign Keys
            $table->foreign('category_id')->references('id')->on('asset_categories')->cascadeOnDelete();
            $table->foreign('type_id')->references('id')->on('asset_types')->nullOnDelete();
            $table->foreign('condition_id')->references('id')->on('asset_conditions')->nullOnDelete();
            $table->foreign('location_id')->references('id')->on('asset_locations')->nullOnDelete();
            $table->foreign('responsible_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('assets');
    }
};
