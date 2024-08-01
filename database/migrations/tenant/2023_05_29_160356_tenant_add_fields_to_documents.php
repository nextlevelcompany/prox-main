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
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedInteger('collect_api_state_id')->default(99)->comment('estado de api en global factoring');
            $table->foreign('collect_api_state_id')->references('id')->on('cat_global_factoring_api_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['collect_api_state_id']);
            $table->dropColumn('collect_api_state_id');
        });
    }
};
