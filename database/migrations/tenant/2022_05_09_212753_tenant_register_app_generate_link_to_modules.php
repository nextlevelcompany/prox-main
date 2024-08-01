<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;

class TenantRegisterAppGenerateLinkToModules extends Migration
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
            self::getSystemModuleConnection()->insert($subscriptionData);
        }
        $subscriptionRow = self::getSystemModuleConnection()->where($subscriptionData)->first();
        if ($subscriptionRow != null) {
            /*$levels = $this->getModuleLevelData($subscriptionRow->id);
            foreach ($levels as $level) {
                $subscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                if ($subscriptionLevelRow === null) {
                    self::getSystemModuleLevelConnection()->insert($level);
                }
            }*/
        }


    }

    public static function getModuleData(): array
    {
        return [
            'value' => 'generate_link_app',
            'description' => 'Generador de link de pago',
            // 'sort' => 19,
            'order_menu' => 19,
        ];
    }

    public static function getSystemModuleConnection(): Builder
    {
        return DB::connection('tenant')->table('modules');
    }

    public function getModuleLevelData($module_id): array
    {
        if (empty($module_id)) {

            echo("No se encuentra el id de modulo\n");
        }
        $data = [];
        $data [] = [
            'value' => 'full_subscription_app_client',
            'description' => 'Cliente',
            'module_id' => $module_id,

        ];

        return $data;
    }

    public static function getSystemModuleLevelConnection(): Builder
    {
        return DB::connection('tenant')->table('module_levels');
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
            /*$levels = $this->getModuleLevelData($subscriptionRow->id);
            foreach ($levels as $level) {
                $subscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                if ($subscriptionLevelRow != null) {

                    DB::connection('tenant')->table('module_levels')->delete($subscriptionLevelRow->id);

                }
            }
            */
            DB::connection('tenant')->table('modules')->delete($subscriptionRow->id);
        }


    }
}
