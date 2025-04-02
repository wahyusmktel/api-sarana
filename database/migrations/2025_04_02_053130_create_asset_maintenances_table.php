<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->string('maintenance_type')->nullable()->comment('Perbaikan, Pembersihan, dll');
            $table->string('performed_by')->nullable();
            $table->double('cost')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('performed_at')->useCurrent();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_maintenances');
    }
};
