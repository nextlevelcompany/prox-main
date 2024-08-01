<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Establishment\Models\Establishment;
use Modules\Establishment\Models\Series;
use Modules\OrderNote\Models\OrderNote;

class TenantAddSeriesNumberToOrderNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_notes', function (Blueprint $table) {
            $table->char('document_type_id', 2)->after('prefix');
            $table->char('series', 4)->after('document_type_id');
            $table->integer('number')->after('series');
        });

        $establishments = Establishment::query()->get();
        foreach ($establishments as $index => $e) {
            $series = Series::query()
                ->where('establishment_id', $e->id)
                ->where('document_type_id', 'U6')
                ->first();

            if($series) {
                OrderNote::query()
                    ->chunk(1000, function ($rows) use ($series) {
                        foreach ($rows as $row) {
                            $row->update([
                                'document_type_id' => 'U6',
                                'series' => $series->number,
                                'number' => $row->id
                            ]);
                        }
                    });
            }
        }

        Schema::table('order_notes', function (Blueprint $table) {
            $table->unique(['soap_type_id', 'series', 'number'], 'order_notes_series_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_notes', function (Blueprint $table) {
            $table->dropUnique('order_notes_series_number_unique');
            $table->dropColumn('document_type_id');
            $table->dropColumn('series');
            $table->dropColumn('number');
        });
    }
}
