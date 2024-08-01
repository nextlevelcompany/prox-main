<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Catalog\Models\District;


class TenantAddDataDistrict250307ToDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $district = District::find('250307');

        if(!$district)
        {
            District::insert([
                'id' => '250307',
                'province_id' => '2503',
                'description' => 'Boqueron',
                'active' => true,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        District::where('id', '250307')->delete();
    }

}
