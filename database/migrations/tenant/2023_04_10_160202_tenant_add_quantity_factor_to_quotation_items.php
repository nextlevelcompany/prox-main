<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddQuantityFactorToQuotationItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
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
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn('quantity_factor');
        });
    }
}
