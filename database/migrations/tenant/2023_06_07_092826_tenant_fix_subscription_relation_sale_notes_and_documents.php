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
        if(Schema::hasColumn('sale_notes', 'user_rel_suscription_plan_id')) {
            Schema::table('sale_notes', function (Blueprint $table) {
                $table->renameColumn('user_rel_suscription_plan_id', 'user_rel_subscription_plan_id');
            });
        }
        if(Schema::hasColumn('documents', 'user_rel_suscription_plan_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->renameColumn('user_rel_suscription_plan_id', 'user_rel_subscription_plan_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
