<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Company\Models\Configuration;
use Modules\Item\Models\CatColorsItem;
use Modules\Item\Models\CatItemMoldCavity;
use Modules\Item\Models\CatItemMoldProperty;
use Modules\Item\Models\CatItemPackageMeasurement;
use Modules\Item\Models\CatItemProductFamily;
use Modules\Item\Models\CatItemSize;
use Modules\Item\Models\CatItemStatus;
use Modules\Item\Models\CatItemUnitBusiness;
use Modules\Item\Models\CatItemUnitsPerPackage;

class ExtraInfoController extends Controller
{
    public function getExtraDataForItems()
    {
        $configuration = Configuration::first();

        /** Informacion adicional */
        $colors = collect([]);
        $CatItemSize = $colors;
        $CatItemStatus = $colors;
        $CatItemUnitBusiness = $colors;
        $CatItemMoldCavity = $colors;
        $CatItemPackageMeasurement = $colors;
        $CatItemUnitsPerPackage = $colors;
        $CatItemMoldProperty = $colors;
        $CatItemProductFamily = $colors;
        if ($configuration->isShowExtraInfoToItem()) {
            $colors = CatColorsItem::all();
            $CatItemSize = CatItemSize::all();
            $CatItemStatus = CatItemStatus::all();
            $CatItemUnitBusiness = CatItemUnitBusiness::all();
            $CatItemMoldCavity = CatItemMoldCavity::all();
            $CatItemPackageMeasurement = CatItemPackageMeasurement::all();
            $CatItemUnitsPerPackage = CatItemUnitsPerPackage::all();
            $CatItemMoldProperty = CatItemMoldProperty::all();
            $CatItemProductFamily = CatItemProductFamily::all();
        }
        $configuration = $configuration->getCollectionData();
        return compact(
            'configuration',
            'colors',
            'CatItemSize',
            'CatItemMoldCavity',
            'CatItemMoldProperty',
            'CatItemUnitBusiness',
            'CatItemStatus',
            'CatItemPackageMeasurement',
            'CatItemProductFamily',
            'CatItemUnitsPerPackage'
        );
    }

}
