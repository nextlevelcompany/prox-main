<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CreateAppSubscription extends Migration
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
                $levels = $this->getModuleLevelData($subscriptionRow->id);
                foreach ($levels as $level) {
                    $subscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                    if ($subscriptionLevelRow === null) {
                        self::getSystemModuleLevelConnection()->insert($level);
                    }
                }
            }


        }

        public static function getModuleData(): array
        {
            return [
                'value' => 'subscription_app',
                'description' => 'Subscriptiones',
                // 'sort' => 16,
                'order_menu' => 16,

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
                'value' => 'subscription_app_client',
                'description' => 'Cliente',
                'module_id' => $module_id,

            ];
            $data [] = [
                'value' => 'subscription_app_service',
                'description' => 'Servicio',
                'module_id' => $module_id,

            ];
            $data [] = [
                'value' => 'subscription_app_payments',
                'description' => 'Pagos',
                'module_id' => $module_id,

            ];
            $data [] = [
                'value' => 'subscription_app_plans',
                'description' => 'Planes',
                'module_id' => $module_id,

            ];
            /*
            $data [] = [
                'value' => 'subscription_app_payments',
                'description' => 'Pagos',
                'module_id' => $module_id,

            ];
            */
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
                $levels = $this->getModuleLevelData($subscriptionRow->id);
                foreach ($levels as $level) {
                    $subscriptionLevelRow = self::getSystemModuleLevelConnection()->where($level)->first();
                    if ($subscriptionLevelRow != null) {
                        DB::connection('tenant')->table('module_levels')->delete($subscriptionLevelRow->id);
                    }
                }
                DB::connection('tenant')->table('modules')->delete($subscriptionRow->id);
            }


        }
    }
