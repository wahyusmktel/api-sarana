<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->string('data_hash')->nullable()->after('document_path');
        });
    }

    public function down()
    {
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->dropColumn('data_hash');
        });
    }
};
