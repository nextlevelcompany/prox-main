<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddUniqueDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        se elimina porque genera conflictos al migrar data de pro5 a X
        
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique('documents_unique_filename_unique');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unique(['soap_type_id', 'document_type_id', 'series', 'number'], 'documents_series_number_unique');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('documents', function (Blueprint $table) {
            $table->dropUnique('documents_series_number_unique');
        });
        */
    }
}
