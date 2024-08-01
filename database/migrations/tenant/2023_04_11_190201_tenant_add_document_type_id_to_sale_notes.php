<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDocumentTypeIdToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->char('document_type_id', 2)->after('prefix');

            $table->unique(['soap_type_id', 'series', 'number'], 'sale_notes_series_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->dropUnique('sale_notes_series_number_unique');
            $table->dropColumn('document_type_id');
        });
    }
}
