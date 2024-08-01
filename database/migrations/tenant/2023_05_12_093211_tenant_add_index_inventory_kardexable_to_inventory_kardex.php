<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_kardex', function (Blueprint $table) {
            $table->index('inventory_kardexable_id');	
            $table->index('inventory_kardexable_type');	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_kardex', function (Blueprint $table) {
            $table->dropIndex(['inventory_kardexable_id']);
            $table->dropIndex(['inventory_kardexable_type']);
        });
    }
};
