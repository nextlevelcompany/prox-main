<?php

use Modules\Catalog\Models\UnitType;
use Illuminate\Database\Migrations\Migration;

class TenantAddUnitTypeToCatUnitTypes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UnitType::query()->updateOrCreate([
            'id' => 'TM'
        ], [
            'description' => 'Toneladas',
            'active' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
