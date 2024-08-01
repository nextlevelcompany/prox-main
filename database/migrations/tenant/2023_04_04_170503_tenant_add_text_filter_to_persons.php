<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Person\Models\Person;

class TenantAddTextFilterToPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->longText('text_filter')->nullable()->after('name');
        });

        Person::query()
            ->chunk(1000, function ($rows) {
                foreach ($rows as $row) {
                    $row->update();
                }
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('text_filter');
        });
    }
}
