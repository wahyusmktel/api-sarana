<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('borrower_user_id');
            $table->text('purpose')->nullable();
            $table->date('loan_start')->nullable();
            $table->date('loan_end')->nullable();
            $table->string('status')->nullable()->comment('Dipinjam, Dikembalikan, Terlambat');
            $table->date('returned_at')->nullable();
            $table->text('notes')->nullable();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
            $table->foreign('borrower_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_loans');
    }
};
