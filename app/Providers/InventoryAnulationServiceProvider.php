<?php

namespace App\Providers;

use Modules\Document\Models\Document;
use Illuminate\Support\ServiceProvider;
use App\Traits\InventoryKardexTrait;

class InventoryAnulationServiceProvider extends ServiceProvider
{
    use InventoryKardexTrait;

    public function register()
    {
    }

    public function boot()
    {
    }

    private function anulation()
    {
        Document::updated(function ($document) {
            if ($document['document_type_id'] == '01' || $document['document_type_id'] == '03') {
                if ($document['state_type_id'] == 11) {
                    foreach ($document['items'] as $detail) {
                        $this->updateStock($detail['item_id'], $document['establishment_id'], $detail['quantity'], false);
                        $this->saveInventoryKardex($document, $detail['item_id'], $document['establishment_id'], -$detail['quantity']);
                    }
                }
            }
        });
    }
}
