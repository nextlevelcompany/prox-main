<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Cash\Models\CashDocument;
use Modules\Cash\Models\CashDocumentCredit;
use Modules\Document\Models\Document;
use Modules\SaleNote\Models\SaleNote;
use Modules\Quotation\Models\Quotation;
use Modules\Purchase\Models\Purchase;
use Modules\Retention\Models\Retention;
use Modules\Perception\Models\Perception;
use Illuminate\Http\Request;
use Modules\Company\Models\Configuration;
use Modules\Expense\Models\Expense;
use Modules\Purchase\Models\PurchaseOrder;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Models\Income;
use Modules\Purchase\Models\PurchaseQuotation;
use Modules\OrderNote\Models\OrderNote;
use Modules\OrderForm\Models\OrderForm;
use Modules\Sale\Models\SaleOpportunity;
use Modules\Sale\Models\Contract;
use Modules\Purchase\Models\FixedAssetPurchase;
use Modules\Payment\Models\PaymentLink;
use Modules\MercadoPago\Models\Transaction;
use Modules\Pos\Models\Tip;
use Modules\Production\Models\Production;
use Modules\Production\Models\Mill;
use Modules\Production\Models\Packaging;
use Modules\Summary\Models\Summary;
use Modules\Voided\Models\Voided;

class OptionController extends Controller
{
    protected $delete_quantity;

    public function create()
    {
        return view('tenant.options.form');
    }

    public function deleteDocuments(Request $request)
    {
        $this->delete_quantity = 0;

        Summary::query()->where('soap_type_id', '01')->delete();
        Voided::query()->where('soap_type_id', '01')->delete();

        //Purchase
        $this->deleteInventoryKardex(Purchase::class);

        Purchase::where('soap_type_id', '01')->delete();

        PurchaseOrder::where('soap_type_id', '01')->delete();
        PurchaseQuotation::where('soap_type_id', '01')->delete();

        $quantity = Document::where('soap_type_id', '01')->count();

        //Document
        $this->deleteInventoryKardex(Document::class);

        Document::where('soap_type_id', '01')
            ->whereIn('document_type_id', ['07', '08'])->delete();

        $this->deleteRecordsCash(Document::class);
        // Document::where('soap_type_id', '01')->delete();

        $this->update_quantity_documents($quantity);

        Retention::where('soap_type_id', '01')->delete();
        Perception::where('soap_type_id', '01')->delete();

        //SaleNote
        $sale_notes = SaleNote::where('soap_type_id', '01')->get();
        // SaleNote::where('soap_type_id', '01')->delete();

        $this->deleteRecordsCash(SaleNote::class);

        $this->deleteInventoryKardex(SaleNote::class, $sale_notes);


        Contract::where('soap_type_id', '01')->delete();
        // Quotation::where('soap_type_id', '01')->delete();
        $this->deleteQuotation();

        SaleOpportunity::where('soap_type_id', '01')->delete();

        Expense::where('soap_type_id', '01')->delete();
        OrderNote::where('soap_type_id', '01')->delete();
        OrderForm::where('soap_type_id', '01')->delete();

        GlobalPayment::where('soap_type_id', '01')->delete();
        Tip::where('soap_type_id', '01')->delete();

        Income::where('soap_type_id', '01')->delete();

        FixedAssetPurchase::where('soap_type_id', '01')->delete();

        $this->updateStockAfterDelete();

        $this->deletePaymentLink();

        // produccion

        Production::where('soap_type_id', '01')->delete();
        Packaging::where('soap_type_id', '01')->delete();
        $this->deleteMill();

        return [
            'success' => true,
            'message' => 'Documentos de prueba eliminados',
            'delete_quantity' => $this->delete_quantity,
        ];
    }


    /**
     *
     * Eliminar links de pago y transacciones asociadas en demo
     *
     * @return void
     */
    private function deletePaymentLink()
    {
        $transactions = Transaction::where('soap_type_id', '01')->get();

        foreach ($transactions as $transaction) {
            $transaction->transaction_queries()->delete();
            $transaction->delete();
        }

        PaymentLink::where('soap_type_id', '01')->delete();
    }


    /**
     *
     * Eliminar registros de ingresos de insumos
     *
     * @return void
     */
    private function deleteMill()
    {
        $mills = Mill::where('soap_type_id', '01')->get();

        foreach ($mills as $mill) {
            $mill->relation_mill_items()->delete();
            $mill->delete();
        }

    }

    /**
     *
     * Eliminar registros relacionados en caja y cotizaciones
     *
     * @return void
     */
    private function deleteQuotation()
    {
        $records_id = Quotation::where('soap_type_id', '01')->whereFilterWithOutRelations()->select('id')->get()->pluck('id')->toArray();
        // dd($records_id);
        CashDocument::whereIn('quotation_id', $records_id)->delete();
        Quotation::where('soap_type_id', '01')->delete();
    }


    /**
     *
     * Eliminar registros relacionados en caja - notas de venta/cpe
     *
     * @return void
     */
    private function deleteRecordsCash($model)
    {
        $records_id = $model::where('soap_type_id', '01')->whereFilterWithOutRelations()->select('id')->get()->pluck('id')->toArray();

        $column = ($model === Document::class) ? 'document_id' : 'sale_note_id';

        CashDocumentCredit::whereIn($column, $records_id)->delete();

        $model::where('soap_type_id', '01')->delete();
    }

    private function deleteInventoryKardex($model, $records = null)
    {
        if (!$records) {
            $records = $model::where('soap_type_id', '01')->get();
        }

        $this->delete_quantity += $records->count();

        foreach ($records as $record) {
            $record->inventory_kardex()->delete();
        }
    }

    private function updateStockAfterDelete()
    {

        // if($this->delete_quantity > 0){

        //     ItemWarehouse::latest()->update([
        //         'stock' => 0
        //     ]);

        // }

    }

    private function update_quantity_documents($quantity)
    {
        $configuration = Configuration::first();
        $configuration->quantity_documents -= $quantity;
        $configuration->save();
    }

}
