<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('document_type')->nullable()->comment('Sertifikat, Faktur, Manual');
            $table->timestamp('uploaded_at')->useCurrent();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_documents');
    }
};
