@php
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
        <td class="text-center desc font-bold">Distribuido por www.awsperutic.com</td>
    </tr>
    <tr>
        <td class="text-center desc font-bold">Contacto: vetas@empiretron.pe / 992 726 963</td>
    </tr>
</table>
</body>