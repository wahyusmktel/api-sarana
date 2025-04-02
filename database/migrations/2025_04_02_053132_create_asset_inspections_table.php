<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('inspected_by');
            $table->string('result')->nullable()->comment('Lolos, Butuh Perbaikan, Tidak Layak');
            $table->text('notes')->nullable();
            $table->timestamp('inspected_at')->useCurrent();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
            $table->foreign('inspected_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_inspections');
    }
};
