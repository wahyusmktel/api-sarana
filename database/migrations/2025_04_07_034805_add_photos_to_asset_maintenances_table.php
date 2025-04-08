<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->string('photo_before')->nullable();
            $table->string('photo_after')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->dropColumn(['photo_before', 'photo_after', 'document_name', 'document_path']);
        });
    }
};
