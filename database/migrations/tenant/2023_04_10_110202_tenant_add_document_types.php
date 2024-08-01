<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Catalog\Models\DocumentType;
use Modules\Establishment\Models\Establishment;
use Modules\Establishment\Models\Series;

class TenantAddDocumentTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cat_document_types', function (Blueprint $table) {
            $table->boolean('is_sunat')->default(true);
        });

        $document_types = [
            ['id' => 'U5', 'name' => 'COTIZACIÓN'],
            ['id' => 'U6', 'name' => 'PEDIDO'],
            ['id' => 'U7', 'name' => 'ORDEN DE COMPRA'],
        ];

        foreach ($document_types as $dt) {
            DocumentType::query()
                ->updateOrCreate([
                    'id' => $dt['id']
                ], [
                    'description' => $dt['name'],
                    'active' => true,
                ]);
        }

        $no_sunat = ['80', 'GU75', 'NE76', 'U2', 'U3', 'U4', 'U5', 'U6', 'U7'];
        DocumentType::query()
            ->whereIn('id', $no_sunat)
            ->update([
                'is_sunat' => false
            ]);

        $establishments = Establishment::query()->get();
        foreach ($establishments as $index => $e) {
            Series::query()->updateOrCreate([
                'document_type_id' => 'U5',
                'establishment_id' => $e->id
            ], [
                'number' => 'C00' . ($index + 1)
            ]);

            Series::query()->updateOrCreate([
                'document_type_id' => 'U6',
                'establishment_id' => $e->id
            ], [
                'number' => 'P00' . ($index + 1)
            ]);

            Series::query()->updateOrCreate([
                'document_type_id' => 'U7',
                'establishment_id' => $e->id
            ], [
                'number' => 'OC0' . ($index + 1)
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
        Schema::table('cat_document_types', function (Blueprint $table) {
            $table->dropColumn('is_sunat');
        });
    }
}
