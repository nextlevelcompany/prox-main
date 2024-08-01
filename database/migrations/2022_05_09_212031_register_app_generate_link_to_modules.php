<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class RegisterAppGenerateLinkToModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subscriptionData = self::getModuleData();
        $subscriptionRow = self::getSystemModuleConnection()->where($subscriptionData)->first();
        if ($subscriptionRow === null) {
            $subscriptionRow = self::getSystemModuleConnection()->insert($subscriptionData);

        }
    }

    public static function getModuleData(): array
    {
        return [
            'value' => 'generate_link_app',
            'description' => 'Generador de link de pago',
            'sort' => 19,
            // 'order_menu' => 19,
        ];
    }

    public static function getSystemModuleConnection(): Builder
    {
        return DB::connection('system')->table('modules');
    }

    public static function getSystemModuleLevelConnection(): Builder
    {
        return DB::connection('system')->table('module_levels');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $subscriptionData = self::getModuleData();

        $subscriptionRow = self::getSystemModuleConnection()->where($subscriptionData)->first();
        if ($subscriptionRow != null) {
            DB::connection('system')->table('modules')->delete($subscriptionRow->id);
        }


    }
}
