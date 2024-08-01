<?php

namespace Modules\Sale\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Document\Models\Document;
use Modules\SaleNote\Models\SaleNote;

class PointSystemProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setPointsToDocument();
        $this->setPointsToSaleNote();
    }


    /**
     *
     * Validar si la nota de venta se usara para sistema por puntos y actualizar puntos del cliente
     *
     * @return void
     */
    private function setPointsToSaleNote()
    {
        // para registro
        SaleNote::created(function ($sale_note) {

            if($sale_note->isCreatedFromPos() && $sale_note->isPointSystem())
            {
                $customer = $sale_note->person;

                // para items que son intercambiados por puntos
                $this->exchangePointsFromItems($customer, -1);

                // para incrementar puntos por venta
                $this->setPointsToCustomer($sale_note, 1, $customer);
            }
        });


        // para anulaciones
        SaleNote::updated(function ($sale_note) {

            if($sale_note->isCreatedFromPos() && $sale_note->isVoidedOrRejected() && $sale_note->isPointSystem())
            {
                $customer = $sale_note->person;

                // para items que son intercambiados por puntos
                $this->exchangePointsFromItems($customer, 1, $sale_note);

                // para restar puntos por venta si es anulada
                $this->setPointsToCustomer($sale_note, -1, $customer);
            }
        });

    }


    /**
     *
     * Validar si el documento se usara para sistema por puntos y actualizar puntos del cliente
     *
     * @return void
     */
    private function setPointsToDocument()
    {

        // para registro del cpe
        Document::created(function ($document) {

            if($document->isDocumentTypeInvoice() && $document->isPointSystem() && !$document->sale_note_id)
            {
                $customer = $document->person;

                // para items que son intercambiados por puntos
                $this->exchangePointsFromItems($customer, -1);

                // para incrementar puntos por venta
                $this->setPointsToCustomer($document, 1, $customer);
            }

        });


        // para anulaciones o rechazo del cpe
        Document::updated(function ($document) {

            if($document->isDocumentTypeInvoice() && $document->isVoidedOrRejected() && $document->isPointSystem() && !$document->sale_note_id)
            {
                $customer = $document->person;

                // para items que son intercambiados por puntos
                $this->exchangePointsFromItems($customer, 1, $document);

                // para restar puntos por venta si es anulada o rechazada
                $this->setPointsToCustomer($document, -1, $customer);
            }
        });

    }

    private function exchangePointsFromItems($customer, $factor, $document = null)
    {
        if($document)
        {
            $total_used_points_for_exchange = $document->items->sum(function($row){
                return $row->item->used_points_for_exchange ?? 0;
            });
        }
        else
        {
            $inputs = request()->all();
            $total_used_points_for_exchange = collect($inputs['items'])->sum(function($row){
                return $row['item']['used_points_for_exchange'] ?? 0;
            });
        }

        if($total_used_points_for_exchange > 0)
        {
            $this->calculateAccumulatedPoints($customer, $total_used_points_for_exchange, $factor);
        }
    }

    private function setPointsToCustomer($document, $factor, $customer)
    {
        $point_system_data = $document->point_system_data;

        $total = $document->total;

        $value_quantity_points = ($total / $point_system_data->point_system_sale_amount) * $point_system_data->quantity_of_points;
        $calculate_quantity_points = $point_system_data->round_points_of_sale ? intval($value_quantity_points) : round($value_quantity_points, 2);

        $this->calculateAccumulatedPoints($customer, $calculate_quantity_points, $factor);
    }

    private function calculateAccumulatedPoints($customer, $calculate_quantity_points, $factor)
    {
        $customer->accumulated_points = $customer->accumulated_points  + ($calculate_quantity_points * $factor);
        $customer->save();
    }
}
