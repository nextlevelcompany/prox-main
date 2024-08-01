<?php

namespace Modules\Subscription\Models;

use Modules\Catalog\Models\AffectationIgvType;
use Modules\Catalog\Models\CurrencyType;
use Modules\Catalog\Models\PriceType;
use Modules\Catalog\Models\SystemIscType;
use Modules\Item\Models\Item;
use App\Models\Tenant\ModelTenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ItemRelSubscriptionPlan
 *
 * @property int $id
 * @property int|null $item_id
 * @property int|null $subscription_plan_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $item
 * @property float|null $quantity
 * @property float|null $unit_value
 * @property string|null $affectation_igv_type_id
 * @property float|null $total_base_igv
 * @property float|null $percentage_igv
 * @property float|null $total_igv
 * @property string|null $system_isc_type_id
 * @property float|null $total_base_isc
 * @property float|null $percentage_isc
 * @property float|null $total_isc
 * @property float|null $total_base_other_taxes
 * @property float|null $percentage_other_taxes
 * @property float|null $total_other_taxes
 * @property float|null $total_taxes
 * @property string|null $price_type_id
 * @property float|null $unit_price
 * @property float|null $total_value
 * @property float|null $total_charge
 * @property float|null $total_discount
 * @property float|null $total
 * @property string|null $discounts
 * @property string|null $charges
 * @property string|null $additional_information
 * @property int|null $warehouse_id
 * @property string|null $name_product_pdf
 * @property Collection|Item[] $relation_item
 * @method static Builder|ItemRelSubscriptionPlan newModelQuery()
 * @method static Builder|ItemRelSubscriptionPlan newQuery()
 * @method static Builder|ItemRelSubscriptionPlan query()
 * * @package App\Models
 * @property-read AffectationIgvType $affectation_igv_type
 * @property-read PriceType $price_type
 * @property-read SystemIscType $system_isc_type
 */
class ItemRelSubscriptionPlan extends ModelTenant
{
    protected $fillable = [
        'item_id',
        'subscription_plan_id',
        'item',
        'quantity',
        'unit_value',
        'affectation_igv_type_id',
        'total_base_igv',
        'percentage_igv',
        'total_igv',
        'system_isc_type_id',
        'total_base_isc',
        'percentage_isc',
        'total_isc',
        'total_base_other_taxes',
        'percentage_other_taxes',
        'total_other_taxes',
        'total_taxes',
        'price_type_id',
        'unit_price',
        'total_value',
        'total_charge',
        'total_discount',
        'total',
        'attributes',
        'discounts',
        'charges',
        'additional_information',
        'warehouse_id',
        'name_product_pdf'
    ];

    protected $casts = [
        'item_id' => 'int',
        'subscription_plan_id' => 'int',
        'quantity' => 'float',
        'unit_value' => 'float',
        'total_base_igv' => 'float',
        'percentage_igv' => 'float',
        'total_igv' => 'float',
        'total_base_isc' => 'float',
        'percentage_isc' => 'float',
        'total_isc' => 'float',
        'total_base_other_taxes' => 'float',
        'percentage_other_taxes' => 'float',
        'total_other_taxes' => 'float',
        'total_taxes' => 'float',
        'unit_price' => 'float',
        'total_value' => 'float',
        'total_charge' => 'float',
        'total_discount' => 'float',
        'total' => 'float',
        'warehouse_id' => 'int'
    ];

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getItemAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setItemAttribute($value)
    {
        $this->attributes['item'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getAttributesAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getChargesAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getDiscountsAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = ($value === null) ? null : json_encode($value);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setQuantity(?float $quantity): ItemRelSubscriptionPlan
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getUnitValue(): ?float
    {
        return $this->unit_value;
    }

    /**
     * @param float|null $unit_value
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setUnitValue(?float $unit_value): ItemRelSubscriptionPlan
    {
        $this->unit_value = $unit_value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAffectationIgvTypeId(): ?string
    {
        return $this->affectation_igv_type_id;
    }

    /**
     * @param string|null $affectation_igv_type_id
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setAffectationIgvTypeId(?string $affectation_igv_type_id): ItemRelSubscriptionPlan
    {
        $this->affectation_igv_type_id = $affectation_igv_type_id;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalBaseIgv(): ?float
    {
        return $this->total_base_igv;
    }

    /**
     * @param float|null $total_base_igv
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalBaseIgv(?float $total_base_igv): ItemRelSubscriptionPlan
    {
        $this->total_base_igv = $total_base_igv;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercentageIgv(): ?float
    {
        return $this->percentage_igv;
    }

    /**
     * @param float|null $percentage_igv
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setPercentageIgv(?float $percentage_igv): ItemRelSubscriptionPlan
    {
        $this->percentage_igv = $percentage_igv;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalIgv(): ?float
    {
        return $this->total_igv;
    }

    /**
     * @param float|null $total_igv
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalIgv(?float $total_igv): ItemRelSubscriptionPlan
    {
        $this->total_igv = $total_igv;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSystemIscTypeId(): ?string
    {
        return $this->system_isc_type_id;
    }

    /**
     * @param string|null $system_isc_type_id
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setSystemIscTypeId(?string $system_isc_type_id): ItemRelSubscriptionPlan
    {
        $this->system_isc_type_id = $system_isc_type_id;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalBaseIsc(): ?float
    {
        return $this->total_base_isc;
    }

    /**
     * @param float|null $total_base_isc
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalBaseIsc(?float $total_base_isc): ItemRelSubscriptionPlan
    {
        $this->total_base_isc = $total_base_isc;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercentageIsc(): ?float
    {
        return $this->percentage_isc;
    }

    /**
     * @param float|null $percentage_isc
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setPercentageIsc(?float $percentage_isc): ItemRelSubscriptionPlan
    {
        $this->percentage_isc = $percentage_isc;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalIsc(): ?float
    {
        return $this->total_isc;
    }

    /**
     * @param float|null $total_isc
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalIsc(?float $total_isc): ItemRelSubscriptionPlan
    {
        $this->total_isc = $total_isc;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalBaseOtherTaxes(): ?float
    {
        return $this->total_base_other_taxes;
    }

    /**
     * @param float|null $total_base_other_taxes
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalBaseOtherTaxes(?float $total_base_other_taxes): ItemRelSubscriptionPlan
    {
        $this->total_base_other_taxes = $total_base_other_taxes;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercentageOtherTaxes(): ?float
    {
        return $this->percentage_other_taxes;
    }

    /**
     * @param float|null $percentage_other_taxes
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setPercentageOtherTaxes(?float $percentage_other_taxes): ItemRelSubscriptionPlan
    {
        $this->percentage_other_taxes = $percentage_other_taxes;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalOtherTaxes(): ?float
    {
        return $this->total_other_taxes;
    }

    /**
     * @param float|null $total_other_taxes
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalOtherTaxes(?float $total_other_taxes): ItemRelSubscriptionPlan
    {
        $this->total_other_taxes = $total_other_taxes;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalTaxes(): ?float
    {
        return $this->total_taxes;
    }

    /**
     * @param float|null $total_taxes
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalTaxes(?float $total_taxes): ItemRelSubscriptionPlan
    {
        $this->total_taxes = $total_taxes;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPriceTypeId(): ?string
    {
        return $this->price_type_id;
    }

    /**
     * @param string|null $price_type_id
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setPriceTypeId(?string $price_type_id): ItemRelSubscriptionPlan
    {
        $this->price_type_id = $price_type_id;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getUnitPrice(): ?float
    {
        return $this->unit_price;
    }

    /**
     * @param float|null $unit_price
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setUnitPrice(?float $unit_price): ItemRelSubscriptionPlan
    {
        $this->unit_price = $unit_price;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalValue(): ?float
    {
        return $this->total_value;
    }

    /**
     * @param float|null $total_value
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalValue(?float $total_value): ItemRelSubscriptionPlan
    {
        $this->total_value = $total_value;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalCharge(): ?float
    {
        return $this->total_charge;
    }

    /**
     * @param float|null $total_charge
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalCharge(?float $total_charge): ItemRelSubscriptionPlan
    {
        $this->total_charge = $total_charge;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalDiscount(): ?float
    {
        return $this->total_discount;
    }

    /**
     * @param float|null $total_discount
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotalDiscount(?float $total_discount): ItemRelSubscriptionPlan
    {
        $this->total_discount = $total_discount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float|null $total
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setTotal(?float $total): ItemRelSubscriptionPlan
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDiscounts(): ?string
    {
        return $this->discounts;
    }

    /**
     * @param string|null $discounts
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setDiscounts(?string $discounts): ItemRelSubscriptionPlan
    {
        $this->discounts = $discounts;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCharges(): ?string
    {
        return $this->charges;
    }

    /**
     * @param string|null $charges
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setCharges(?string $charges): ItemRelSubscriptionPlan
    {
        $this->charges = $charges;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalInformation(): ?string
    {
        return $this->additional_information;
    }

    /**
     * @param string|null $additional_information
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setAdditionalInformation(?string $additional_information): ItemRelSubscriptionPlan
    {
        $this->additional_information = $additional_information;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWarehouseId(): ?int
    {
        return $this->warehouse_id;
    }

    /**
     * @param int|null $warehouse_id
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setWarehouseId(?int $warehouse_id): ItemRelSubscriptionPlan
    {
        $this->warehouse_id = $warehouse_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNameProductPdf(): ?string
    {
        return $this->name_product_pdf;
    }

    /**
     * @param string|null $name_product_pdf
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setNameProductPdf(?string $name_product_pdf): ItemRelSubscriptionPlan
    {
        $this->name_product_pdf = $name_product_pdf;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription_plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * @return int|null
     */
    public function getItemId(): ?int
    {
        return $this->item_id;
    }

    /**
     * @param int|null $item_id
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setItemId(?int $item_id): ItemRelSubscriptionPlan
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSubscriptionPlanId(): ?int
    {
        return $this->subscription_plan_id;
    }

    /**
     * @param int|null $subscription_plan_id
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setSubscriptionPlanId(?int $subscription_plan_id): ItemRelSubscriptionPlan
    {
        $this->subscription_plan_id = $subscription_plan_id;
        return $this;
    }

    /**
     * @return Item|null
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }

    /**
     * @param Item|null $item
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setItem(?Item $item): ItemRelSubscriptionPlan
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return SubscriptionPlan|null
     */
    public function getSubscriptionPlan(): ?SubscriptionPlan
    {
        return $this->subscription_plan;
    }

    /**
     * @param SubscriptionPlan|null $subscription_plan
     *
     * @return ItemRelSubscriptionPlan
     */
    public function setSubscriptionPlan(?SubscriptionPlan $subscription_plan): ItemRelSubscriptionPlan
    {
        $this->subscription_plan = $subscription_plan;
        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionData(CurrencyType $currencyType = null)
    {
        $data = $this->toArray();
        $item = $this->relation_item;
        $relationItem = [];
        if ($item != null) {
            $relationItem = $item->getCollectionData();
        }
        $data = array_merge($data, $relationItem, $this->getTransformItem());
        $data['affectation_igv_type'] = $this->affectation_igv_type();
        $data['currency_type'] = $currencyType;


        return $data;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getTransformItem($warehouse_id = 0)
    {
        $resource = $this->relation_item;

        $data_lots = [
            'lots' => [],
            // 'lots' => $resource->item_lots->where('has_sale', false)->where('warehouse_id', $warehouse_id)->transform(function($row) {
            //     return [
            //         'id' => $row->id,
            //         'series' => $row->series,
            //         'date' => $row->date,
            //         'item_id' => $row->item_id,
            //         'warehouse_id' => $row->warehouse_id,
            //         'has_sale' => (bool)$row->has_sale,
            //         'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code:null):null
            //     ];
            // })->values(),
            'series_enabled' => (bool)(($resource != null) ? $resource->series_enabled : false),
        ];

        return $data_lots;
    }
}
