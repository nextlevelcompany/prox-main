<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Query\Builder;

    class CreateAppRestaurant extends Migration
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
                'value' => 'restaurant_app',
                'description' => 'Restaurante',
                'sort' => 18,
                // 'order_menu' => 17,

            ];
        }

        public static function getSystemModuleConnection(): Builder
        {
            return DB::connection('system')->table('modules');
        }

        public function getModuleLevelData($module_id): array
        {
            if (empty($module_id)) {

                echo("No se encuentra el id de modulo\n");
            }
            $data = [];
            $data [] = [
                'value' => 'restaurant_menu',
                'description' => 'Menu de Restaurante',
                'module_id' => $module_id,

            ];

            return $data;
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



        }
    }
