<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Modules\Document\Models\Document;
use Modules\Purchase\Models\Purchase;
use Modules\SaleNote\Models\SaleNote;
use Modules\OrderNote\Models\OrderNote;
use Modules\Dispatch\Models\Dispatch;
use Modules\Purchase\Models\PurchaseSettlement;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
            ['new' => 'Modules\\\\Document\\\\Models\\\\Document', 'old' => 'App\\\\Models\\\\Tenant\\\\Document'],
            ['new' => 'Modules\\\\Purchase\\\\Models\\\\Purchase', 'old' => 'App\\\\Models\\\\Tenant\\\\Purchase'],
            ['new' => 'Modules\\\\SaleNote\\\\Models\\\\SaleNote', 'old' => 'App\\\\Models\\\\Tenant\\\\SaleNote'],
            ['new' => 'Modules\\\\OrderNote\\\\Models\\\\OrderNote', 'old' => 'Modules\\\\Order\\\\Models\\\\OrderNote'],
            ['new' => 'Modules\\\\Dispatch\\\\Models\\\\Dispatch', 'old' => 'App\\\\Models\\\\Tenant\\\\Dispatch'],
            ['new' => 'Modules\\\\Purchase\\\\Models\\\\PurchaseSettlement', 'old' => 'App\\\\Models\\\\Tenant\\\\PurchaseSettlement'],
        ];
        
        foreach ($old_models as $value)
        {
            $new_model = $value['new'];
            $old_model = $value['old'];

            $sql_update = $this->getSqlUpdate('inventory_kardex', 'inventory_kardexable_type', $new_model, $old_model);
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
    }
};
