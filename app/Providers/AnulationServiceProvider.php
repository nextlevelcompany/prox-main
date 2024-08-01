<?php

namespace App\Providers;

use Modules\Document\Models\Document;
use Modules\Purchase\Models\Purchase;
use Illuminate\Support\ServiceProvider;
use App\Traits\KardexTrait;

class AnulationServiceProvider extends ServiceProvider
{
    use KardexTrait;

    public function register()
    {

    }

    public function boot()
    {
        $this->anulation();
    }

    private function anulation()
    {
        Document::updated(function ($document) {
            if ($document['document_type_id'] == '01' || $document['document_type_id'] == '03') {
                if ($document['state_type_id'] == 11) {
                    foreach ($document['items'] as $detail) {
                        $this->updateStock($detail['item_id'], $detail['quantity'], false);
                        $this->saveKardex('sale', $detail['item_id'], $document['id'], -$detail['quantity'], 'document');
                    }
                }
            }
        });
    }

    private function anulation_purchase()
    {
        Purchase::updated(function ($document) {
            if ($document['state_type_id'] == 11) {
                foreach ($document['items'] as $detail) {
                    $this->updateStock($detail['item_id'], $detail['quantity'], true);
                }
            }
        });
    }
}
