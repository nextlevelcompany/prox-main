<?php

namespace App\Providers;

use Modules\Item\Models\Item;
use Modules\Document\Models\DocumentItem;
use Modules\Document\Models\Document;
use Modules\Purchase\Models\PurchaseItem;
use Modules\Purchase\Models\PurchaseSettlementItem;
use Modules\SaleNote\Models\SaleNoteItem;
use Illuminate\Support\ServiceProvider;
use App\Traits\KardexTrait;

class KardexServiceProvider extends ServiceProvider
{
    use KardexTrait;

    public function boot()
    {
        $this->save_item();
        $this->sale();
        $this->purchase();
        $this->purchase_settlement();
        $this->sale_note();
    }

    public function register()
    {

    }

    private function sale()
    {
        DocumentItem::created(function (DocumentItem $document_item) {
            $document = Document::whereIn('document_type_id', ['01', '03'])->find($document_item->document_id);
            if ($document) {
                $kardex = $this->saveKardex('sale', $document_item->item_id, $document_item->document_id, $document_item->quantity, 'document');
                if ($document->state_type_id != 11) {
                    $this->updateStock($document_item->item_id, $kardex->quantity, true);
                }
            }
        });
    }

    /**
     *Cuando se realiza una compra
     */
    private function purchase()
    {
        PurchaseItem::created(function (PurchaseItem $purchase_item) {
            $kardex = $this->saveKardex('purchase', $purchase_item->item_id, $purchase_item->purchase_id, $purchase_item->quantity, 'purchase');
            $this->updateStock($purchase_item->item_id, $kardex->quantity, false);
        });
    }

    /**
     *Cuando se realiza una compra
     */
    private function purchase_settlement()
    {
        PurchaseSettlementItem::created(function (PurchaseSettlementItem $purchase_item_settlement) {
            $kardex = $this->saveKardex('purchase', $purchase_item_settlement->item_id, $purchase_item_settlement->purchase_settlement_id, $purchase_item_settlement->quantity, 'purchase_settlement');
            $this->updateStock($purchase_item_settlement->item_id, $kardex->quantity, false);
        });
    }

    /**
     * Cuando se realiza una nota de compra
     */
    private function sale_note()
    {
        SaleNoteItem::created(function (SaleNoteItem $sale_note_item) {
            $kardex = $this->saveKardex('sale', $sale_note_item->item_id, $sale_note_item->sale_note_id, $sale_note_item->quantity, 'sale_note');
            $this->updateStock($sale_note_item->item_id, $kardex->quantity, true);
        });
    }

    /**
     * Cuando se guarda un item
     */
    private function save_item()
    {
        Item::created(function (Item $item) {
            $stock = ($item->stock) ? $item->stock : 0;
            $kardex = $this->saveKardex(null, $item->id, null, $stock, null);
        });
    }
}
