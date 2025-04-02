<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_responsible_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('previous_user_id')->nullable()->comment('User yang sebelumnya bertanggung jawab');
            $table->uuid('new_user_id')->comment('User baru yang ditunjuk');
            $table->timestamp('changed_at')->useCurrent();
            $table->text('note')->nullable();

            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete();
            $table->foreign('previous_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('new_user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('asset_responsible_histories');
    }
};
