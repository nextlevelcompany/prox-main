<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\System\Models\Plan;
use Modules\System\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            'name' => 'Admin Instrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        DB::table('plan_documents')->insert([
            ['id' => 1, 'description' => 'Facturas, boletas, notas de débito y crédito, resúmenes y anulaciones'],
            ['id' => 2, 'description' => 'Guias de remisión'],
            ['id' => 3, 'description' => 'Retenciones'],
            ['id' => 4, 'description' => 'Percepciones']
        ]);

        Plan::query()->create([
            'name' => 'Ilimitado',
            'pricing' => 99,
            'limit_users' => 0,
            'limit_documents' => 0,
            'plan_documents' => [1, 2, 3, 4],
            'locked' => true
        ]);
    }
}
