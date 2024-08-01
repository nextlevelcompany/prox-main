<?php

namespace Modules\Document\Http\Controllers;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\CoreFacturalo\Facturalo;
use Modules\Document\Models\Document;
use Modules\Quotation\Http\Controllers\QuotationController;
use Modules\Quotation\Models\Quotation;
use Modules\SaleNote\Http\Controllers\SaleNoteController;
use Modules\SaleNote\Models\SaleNote;
use Modules\Summary\Models\Summary;
use Modules\Voided\Models\Voided;
use Modules\Dispatch\Models\Dispatch;
use Modules\Retention\Models\Retention;
use Modules\Perception\Models\Perception;
use Modules\Purchase\Models\PurchaseSettlement;
use Exception;


class DownloadController extends Controller
{
    use StorageDocument;

    public function downloadExternal($model, $type, $external_id, $format = null)
    {
        $query = $this->getModel($model);
        $document = $query->where('external_id', $external_id)->first();

        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");

        if ($format != null) $this->reloadPDF($document, 'invoice', $format);

        if (in_array($document->document_type_id, ['09', '31']) && $type === 'cdr') {
            $type = 'cdr_xml';
        }
        return $this->download($type, $document);
    }

    public function download($type, $document)
    {
        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr_xml':
                $folder = 'cdr_xml';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            case 'quotation':
                $folder = 'quotation';
                break;
            case 'sale_note':
                $folder = 'sale_note';
                break;

            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }

        return $this->downloadStorage($document->filename, $folder);
    }

    public function toPrint($model, $external_id, $format = 'a4')
    {
        $document_type = $model;
        $query = $this->getModel($model);

        $document = $query->where('external_id', $external_id)->first();

        if (!$document) {
            throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        }

        if ($document_type == 'quotation') {
            // Las cotizaciones tienen su propio controlador, si se generan por este medio, dará error
            $quotation = new QuotationController();
            return $quotation->toPrint($external_id, $format);
        } elseif ($document_type == 'salenote') {
            $saleNote = new SaleNoteController();
            return $saleNote->toPrint($external_id, $format);
        }
        $type = 'invoice';
        if ($document_type == 'dispatch') {
            $type = 'dispatch';
        }
        if ($document->document_type_id === '07') {
            $type = 'credit';
        }
        if ($document->document_type_id === '08') {
            $type = 'debit';
        }

        $this->reloadPDF($document, $type, $format);

        $temp = tempnam(sys_get_temp_dir(), 'pdf');

        file_put_contents($temp, $this->getStorage($document->filename, 'pdf'));

        /*
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$document->filename.'.pdf'.'"'
        ];
        */

        return response()->file($temp, $this->generalPdfResponseFileHeaders($document->filename));
    }

    private function getModel($document_type)
    {
        $model = null;

        switch ($document_type)
        {
            case 'document':
                $model = Document::query();
                break;

            case 'quotation':
                $model = Quotation::query();
                break;

            case 'salenote':
                $model = SaleNote::query();
                break;

            case 'summary':
                $model = Summary::query();
                break;

            case 'voided':
                $model = Voided::query();
                break;

            case 'dispatch':
                $model = Dispatch::query();
                break;

            case 'retention':
                $model = Retention::query();
                break;
                
            case 'perception':
                $model = Perception::query();
                break;

            case 'purchaseSettlement':
                $model = PurchaseSettlement::query();
                break;
        }

        if($model) return $model;

        throw new Exception("El modelo es incorrecto");
    }

    public function toTicket($model, $external_id, $format = null)
    {
        $query = $this->getModel($model);

        $document = $query->where('id', $external_id)->first();

        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");

        if ($format != null) return $this->reloadTicket($document, 'invoice', $format);
    }

    private function reloadTicket($document, $type, $format)
    {
        return (new Facturalo)->createPdf($document, $type, $format, 'html');
    }

    private function reloadPDF($document, $type, $format)
    {
        (new Facturalo)->createPdf($document, $type, $format);
    }
}
