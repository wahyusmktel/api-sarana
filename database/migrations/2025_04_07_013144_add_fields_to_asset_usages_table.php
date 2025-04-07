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
        Schema::table('asset_usages', function (Blueprint $table) {
            $table->timestamp('finished_at')->nullable()->after('used_at');
            $table->string('condition_after')->nullable()->after('finished_at');
            $table->string('photo')->nullable()->after('condition_after');
        });
    }

    public function down(): void
    {
        Schema::table('asset_usages', function (Blueprint $table) {
            $table->dropColumn(['finished_at', 'condition_after', 'photo']);
        });
    }
};
