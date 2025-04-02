<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_room_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('location_id');
            $table->timestamp('assigned_at')->useCurrent();
            $table->text('note')->nullable();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
            $table->foreign('location_id')->references('id')->on('asset_locations')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_room_assignments');
    }
};
