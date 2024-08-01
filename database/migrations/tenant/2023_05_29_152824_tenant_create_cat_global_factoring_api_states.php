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
        Schema::create('cat_global_factoring_api_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('estado de api');
        });

        DB::table('cat_global_factoring_api_states')->insert([
            ['id' => 1, 'name' => 'En Evaluación'],
            ['id' => 2, 'name' => 'Aprobado'],
            ['id' => 3, 'name' => 'Rechazado por Pagador'],
            ['id' => 4, 'name' => 'Rechazado por área de riesgo'],
            ['id' => 5, 'name' => 'Desembolsado'],
            ['id' => 6, 'name' => 'Pagado'],
            ['id' => 90, 'name' => 'No existe Documento'],
            ['id' => 99, 'name' => 'No enviado'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_global_factoring_api_states');
    }
};
