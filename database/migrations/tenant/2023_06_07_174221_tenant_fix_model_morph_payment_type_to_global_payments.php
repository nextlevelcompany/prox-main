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
        $old_models = [
            ['new' => 'Modules\\\\SaleNote\\\\Models\\\\SaleNotePayment', 'old' => 'App\\\\Models\\\\Tenant\\\\SaleNotePayment'],
            ['new' => 'Modules\\\\Document\\\\Models\\\\DocumentPayment', 'old' => 'App\\\\Models\\\\Tenant\\\\DocumentPayment'],
            ['new' => 'Modules\\\\Purchase\\\\Models\\\\PurchasePayment', 'old' => 'App\\\\Models\\\\Tenant\\\\PurchasePayment'],
        ];

        foreach ($old_models as $value)
        {
            $new_model = $value['new'];
            $old_model = $value['old'];

            $sql_update = $this->getSqlUpdate('global_payments', 'payment_type', $new_model, $old_model);
            $run = $this->runSql($sql_update);
        }
    }


    /**
     *
     * @param  string $table
     * @param  string $column
     * @param  string $new_model
     * @param  string $old_model
     * @return string
     */
    private function getSqlUpdate($table, $column, $new_model, $old_model)
    {
        return "UPDATE {$table}  SET {$column} = '{$new_model}' WHERE {$column} = '{$old_model}'";
    }


    /**
     *
     * @param  string $sql
     * @return void
     */
    private function runSql($sql)
    {
        return DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_payments', function (Blueprint $table) {
            //
        });
    }
};