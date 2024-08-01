<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CreateAppProduction extends Migration
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


        }

        public static function getModuleData(): array
        {
            return [
                'value' => 'production_app',
                'description' => 'Producción',
                 // 'sort' => 17,
                 'order_menu' => 17,

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
                'value' => 'production_menu',
                'description' => 'Menu de producción',
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

        }
    }
