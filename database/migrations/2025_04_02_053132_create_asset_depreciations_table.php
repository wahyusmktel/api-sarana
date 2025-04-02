<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_depreciations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->integer('year');
            $table->double('depreciation_amount')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('calculated_at')->useCurrent();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_depreciations');
    }
};
