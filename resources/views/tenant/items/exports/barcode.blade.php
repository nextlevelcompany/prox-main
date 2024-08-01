@php
    $colour = [0,0,0];
    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($item->barcode, $generator::TYPE_CODE_128, 2, 80, $colour)) . '">';
@endphp
<br>
{{$item->barcode}}