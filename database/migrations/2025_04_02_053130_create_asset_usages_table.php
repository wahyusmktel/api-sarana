<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('user_id');
            $table->string('usage_type')->nullable()->comment('Dipakai untuk, pindah lokasi, dsb');
            $table->text('description')->nullable();
            $table->timestamp('used_at')->useCurrent();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_usages');
    }
};
