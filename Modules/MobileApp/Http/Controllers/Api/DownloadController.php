<?php

namespace Modules\MobileApp\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\CoreFacturalo\Facturalo;
use Exception;
use Html2Text\Html2Text;
use Illuminate\Http\Request;
use Modules\SaleNote\Http\Controllers\Api\SaleNoteController;
use Modules\Document\Models\Document;
use Modules\SaleNote\Models\SaleNote;


class DownloadController extends Controller
{

    /**
     *
     * Retornar pdf en html
     *
     * @param  string $model
     * @param  string $external_id
     * @param  string $format
     * @return string
     */
    public function documentPrintPdf($model, $external_id, $format, $extend_pdf_height = 0)
    {
        $query = $this->getQueryModel($model);
        $document = $query->where('external_id', $external_id)->first();

        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");

        $html = $this->getHtmlPdf($model, $document, $format);

        $this->replaceElementsInHtml($html, $format, $extend_pdf_height);

        return $html;
    }

    
    /**
     * 
     * @param  string $model
     * @return void
     */
    private function getQueryModel($model)
    {
        $query = null;

        switch ($model)
        {
            case 'document':
                $query = Document::query();
                break;

            case 'saleNote':
                $query = SaleNote::query();
                break;
        }

        if($query) return $query;

        throw new Exception('El modelo es incorrecto');
    }

    
    /**
     *
     * Reemplazar ancho en formato pdf - altura adicional para ticket (impresion directa app)
     *
     * @param  string $html
     * @param  string $format
     * @param  float $extend_pdf_height
     * @return void
     */
    private function replaceElementsInHtml(&$html, $format, $extend_pdf_height)
    {
        // se reemplaza ancho para impresion desde app para tickets
        $size_width = $this->getSizeWidth($format);

        if($size_width)
        {
            $search_key = '<style>';
            $replace_size = "{$search_key} @media print { .page, .page-content, html, body, .framework7-root, .views, .view { height: auto !important; width: {$size_width}mm !important;}}";

            $html = str_replace($search_key, $replace_size, $html);
        }

        // se agrega un div para aumentar la altura del pdf, se utiliza para impresion directa desde app
        if($extend_pdf_height > 0)
        {
            $search_key_extend = '</body>';
            $replace_size_extend = "<div style='height:".$extend_pdf_height."px'></div>{$search_key_extend}";

            $html = str_replace($search_key_extend, $replace_size_extend, $html);
        }
    }


    /**
     *
     * Obtener medida del formato ticket para asignar el valor a la impresión
     *
     * @param  string $format
     * @return float
     */
    public function getSizeWidth($format)
    {
        $size_width = null;

        switch ($format)
        {
            case 'ticket_50':
                $size_width = 45;
                break;

            case 'ticket_58':
                $size_width = 56;
                break;

            case 'ticket':
                $size_width = 78;
                break;
        }

        return $size_width;
    }


    /**
     *
     * Reload Ticket
     *
     * @param  string $document
     * @param  string $format
     * @return string
     */
    private function getHtmlPdf($model, $document, $format)
    {
        $html = null;

        if($model === 'document')
        {
            $html = (new Facturalo)->createPdf($document, 'invoice', $format, 'html');
        }
        else
        {
            $html = app(SaleNoteController::class)->createPdf($document, $format, null, 'html');
        }

        return $html;
    }


    /**
     *
     * Retornar pdf en texto
     *
     * @param  string $model
     * @param  string $external_id
     * @param  string $format
     * @return string
     */
    public function documentPrintText($model, $external_id, $format)
    {
        $html = $this->documentPrintPdf($model, $external_id, $format);

        return trim((new Html2Text($html))->getText());
    }
}
