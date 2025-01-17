<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\System\Models\Module;
use Modules\System\Models\ModuleLevel;

class AddAppToModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection('system')->table('modules')->insert([
            ['value' => 'apps', 'description' => 'Apps']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $q = Module::where('value', 'apps')->first();
        $q->delete();
    }
}
