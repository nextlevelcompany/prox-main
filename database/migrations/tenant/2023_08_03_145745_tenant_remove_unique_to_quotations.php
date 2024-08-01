<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement('ALTER TABLE quotations DROP INDEX quotations_series_number_unique');
        Schema::table('quotations', function (Blueprint $table) {
            $table->index('soap_type_id');
            $table->dropUnique('quotations_series_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropIndex('soap_type_id');
            $table->unique(['soap_type_id', 'series', 'number'], 'quotations_series_number_unique');
        });
    }
};