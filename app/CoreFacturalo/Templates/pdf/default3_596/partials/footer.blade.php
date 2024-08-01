@php
    if ($document != null) {
        $establishment = $document->establishment;
        $customer = $document->customer;
        $invoice = $document->invoice;
        $document_base = ($document->note) ? $document->note : null;

        //$path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
        $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);

        if($document_base) {

            $affected_document_number = ($document_base->affected_document) ? $document_base->affected_document->series.'-'.str_pad($document_base->affected_document->number, 8, '0', STR_PAD_LEFT) : $document_base->data_affected_document->series.'-'.str_pad($document_base->data_affected_document->number, 8, '0', STR_PAD_LEFT);

        } else {

            $affected_document_number = null;
        }

        $payments = $document->payments;

        // $document->load('reference_guides');

        if ($document->payments) {
            $total_payment = $document->payments->sum('payment');
            $balance = ($document->total - $total_payment) - $document->payments->sum('change');
        }


    }

    use Modules\Template\Helpers\TemplatePdf;
    $accounts = (new TemplatePdf)->getBankAccountsForPdf($document->establishment_id);

    $path_style = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'style.css');
@endphp
<head>
    <link href="{{ $path_style }}" rel="stylesheet" />
</head>
<body>
<table class="full-width">
    <tr>
        <td class="text-center desc font-bold">Para consultar el comprobante ingresar a {!! url('/buscar') !!}</td>
    </tr>
    <tr>
        <td class="text-center desc font-bold">Sistema de Facturación Electrónica distribuido por www.awsperutic.com</td>
    </tr>
    <tr>
        <td class="text-center desc font-bold">Contacto: ventas@awsperutic.com / 916511757</td>
    </tr>
</table>
</body>
