<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddQuantityFactorToSaleNoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_note_items', function (Blueprint $table) {
            $table->decimal('quantity_factor', 12, 4)->default(1)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_note_items', function (Blueprint $table) {
            $table->dropColumn('quantity_factor');
        });
    }
}
