<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Establishment\Models\Establishment;
use Modules\Establishment\Models\Series;
use Modules\Quotation\Models\Quotation;

class TenantAddSeriesNumberToQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->char('document_type_id', 2)->after('prefix');
            $table->char('series', 4)->after('document_type_id');
            $table->integer('number')->after('series');
        });

        $establishments = Establishment::query()->get();
        foreach ($establishments as $index => $e) {
            $series = Series::query()
                ->where('establishment_id', $e->id)
                ->where('document_type_id', 'U5')
                ->first();

            if($series) {
                Quotation::query()
                    ->chunk(1000, function ($rows) use($series) {
                        foreach ($rows as $row) {
                            $row->update([
                                'document_type_id' => 'U5',
                                'series' => $series->number,
                                'number' => $row->id
                            ]);
                        }
                    });
            }
        }

        Schema::table('quotations', function (Blueprint $table) {
            $table->unique(['soap_type_id', 'series', 'number'], 'quotations_series_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropUnique('quotations_series_number_unique');
            $table->dropColumn('document_type_id');
            $table->dropColumn('series');
            $table->dropColumn('number');
        });
    }
}
