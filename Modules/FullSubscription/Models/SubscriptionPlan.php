<?php

namespace Modules\FullSubscription\Models;

use Modules\Catalog\Models\CurrencyType;
use App\Models\Tenant\ModelTenant;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends ModelTenant
{
    protected $perPage = 25;

    protected $fillable = [
        'quantity_period',
        'cat_period_id',
        'name',
        'currency_type_id',
        'payment_method_type_id',
        'exchange_rate_sale',
        'total_prepayment',
        'total_charge',
        'total_discount',
        'total_exportation',
        'total_free',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_igv_free',
        'total_base_isc',
        'total_isc',
        'total_base_other_taxes',
        'total_other_taxes',
        'total_taxes',
        'total_value',
        'charges',
        'attributes',
        'discounts',
        'prepayments',
        'related',
        'perception',
        'detraction',
        'legends',
        'terms_condition',
        'description',
        'total'
    ];

    protected $casts = [
        'quantity_period' => 'int',
        'cat_period_id' => 'int',
        'exchange_rate_sale' => 'float',
        'total_prepayment' => 'float',
        'total_charge' => 'float',
        'total_discount' => 'float',
        'total_exportation' => 'float',
        'total_free' => 'float',
        'total_taxed' => 'float',
        'total_unaffected' => 'float',
        'total_exonerated' => 'float',
        'total_igv' => 'float',
        'total_igv_free' => 'float',
        'total_base_isc' => 'float',
        'total_isc' => 'float',
        'total_base_other_taxes' => 'float',
        'total_other_taxes' => 'float',
        'total_taxes' => 'float',
        'total_value' => 'float',
        'total' => 'float'
    ];


    /**
     * @param $value
     *
     * @return null
     */
    public function getAttributesAttribute($value)
    {
        return ($value === null) ? null : json_decode($value);
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
     * @param $value
     *
     * @return object|null
     */
    public function getPrepaymentsAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getRelatedAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getPerceptionAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getDetractionAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @param $value
     *
     * @return object|null
     */
    public function getLegendsAttribute($value)
    {
        return ($value === null) ? null : (object)json_decode($value);
    }

    /**
     * @param $value
     */
    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = ($value === null) ? null : json_encode($value);
    }

    /**
     * @return int|null
     */
    public function getCatPeriodId(): ?int
    {
        return $this->cat_period_id;
    }

    /**
     * @param int|null $cat_period_id
     *
     * @return SubscriptionPlan
     */
    public function setCatPeriodId(?int $cat_period_id): SubscriptionPlan
    {
        $this->cat_period_id = $cat_period_id;
        return $this;
    }


    /**
     * @return int|null
     */
    public function getQuantityPeriod(): ?int
    {
        return $this->quantity_period;
    }

    /**
     * @param int|null $quantity_period
     *
     * @return SubscriptionPlan
     */
    public function setQuantityPeriod(?int $quantity_period): SubscriptionPlan
    {
        $this->quantity_period = $quantity_period;
        return $this;
    }

    /**
     * @return CatPeriod
     */
    public function getCatPeriod(): CatPeriod
    {
        return CatPeriod::find($this->cat_period_id);
    }

    /**
     * @param CatPeriod $cat_period
     *
     * @return SubscriptionPlan
     */
    public function setCatPeriod(CatPeriod $cat_period): SubscriptionPlan
    {
        $this->cat_period_id = $cat_period->id;
        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function cat_period()
    {
        return $this->belongsTo(CatPeriod::class);
    }

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(ItemRelSubscriptionPlan::class, 'subscription_plan_id');

        /*
        return $this->belongsToMany(Item::class, 'item_rel_subscription_plans')
            ->withPivot('id')
            ->withTimestamps();
        */
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_rel_subscription_plans')
            ->withPivot('id', 'cat_period_id', 'items_text', 'items', 'editable', 'deletable', 'start_date')
            ->withTimestamps();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): SubscriptionPlan
    {
        $this->name = ucfirst(trim($name));
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return SubscriptionPlan
     */
    public function setDescription(?string $description): SubscriptionPlan
    {
        $this->description = $description;
        return $this;
    }


    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return (float)$this->total;
    }

    /**
     * @param float|null $total
     *
     * @return SubscriptionPlan
     */
    public function setTotal(?float $total): SubscriptionPlan
    {
        $this->total = (float)$total;
        return $this;
    }

    /**
     * @return array
     */
    public function getCollectionData()
    {


        $currencyType = $this->currency_type;
        if (empty($this->currency_type_id)) $currencyType = CurrencyType::find('PEN');

        $items = $this->items->transform(function ($item) use ($currencyType) {
            return $item->getCollectionData($currencyType);
        });
        $data = [
            'id' => $this->id,
            'cat_period_id' => $this->cat_period_id,
            'name' => $this->name,
            'items' => $items,
            'description' => $this->description,
            'period' => $this->cat_period->name,
            'currency_type' => $currencyType,
            'periods' => $this->cat_period->period,

        ];
        $data['hasSubscription'] = (bool)count(UserRelSubscriptionPlan::where('subscription_plan_id', $this->id)->get()) > 0;
        $data = array_merge($data, $this->toArray());
        return $data;
    }

    public function currency_type(): BelongsTo
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    /**
     * @return string|null
     */
    public function getCurrencyTypeId(): ?string
    {
        return $this->currency_type_id;
    }

    /**
     * @param string|null $currency_type_id
     *
     * @return SubscriptionPlan
     */
    public function setCurrencyTypeId(?string $currency_type_id): SubscriptionPlan
    {
        $this->currency_type_id = $currency_type_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethodTypeId(): ?string
    {
        return $this->payment_method_type_id;
    }

    /**
     * @param string|null $payment_method_type_id
     *
     * @return SubscriptionPlan
     */
    public function setPaymentMethodTypeId(?string $payment_method_type_id): SubscriptionPlan
    {
        $this->payment_method_type_id = $payment_method_type_id;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getExchangeRateSale(): ?float
    {
        return $this->exchange_rate_sale;
    }

    /**
     * @param float|null $exchange_rate_sale
     *
     * @return SubscriptionPlan
     */
    public function setExchangeRateSale(?float $exchange_rate_sale): SubscriptionPlan
    {
        $this->exchange_rate_sale = $exchange_rate_sale;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalPrepayment(): ?float
    {
        return $this->total_prepayment;
    }

    /**
     * @param float|null $total_prepayment
     *
     * @return SubscriptionPlan
     */
    public function setTotalPrepayment(?float $total_prepayment): SubscriptionPlan
    {
        $this->total_prepayment = $total_prepayment;
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
     * @return SubscriptionPlan
     */
    public function setTotalCharge(?float $total_charge): SubscriptionPlan
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
     * @return SubscriptionPlan
     */
    public function setTotalDiscount(?float $total_discount): SubscriptionPlan
    {
        $this->total_discount = $total_discount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalExportation(): ?float
    {
        return $this->total_exportation;
    }

    /**
     * @param float|null $total_exportation
     *
     * @return SubscriptionPlan
     */
    public function setTotalExportation(?float $total_exportation): SubscriptionPlan
    {
        $this->total_exportation = $total_exportation;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalFree(): ?float
    {
        return $this->total_free;
    }

    /**
     * @param float|null $total_free
     *
     * @return SubscriptionPlan
     */
    public function setTotalFree(?float $total_free): SubscriptionPlan
    {
        $this->total_free = $total_free;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalTaxed(): ?float
    {
        return $this->total_taxed;
    }

    /**
     * @param float|null $total_taxed
     *
     * @return SubscriptionPlan
     */
    public function setTotalTaxed(?float $total_taxed): SubscriptionPlan
    {
        $this->total_taxed = $total_taxed;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalUnaffected(): ?float
    {
        return $this->total_unaffected;
    }

    /**
     * @param float|null $total_unaffected
     *
     * @return SubscriptionPlan
     */
    public function setTotalUnaffected(?float $total_unaffected): SubscriptionPlan
    {
        $this->total_unaffected = $total_unaffected;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalExonerated(): ?float
    {
        return $this->total_exonerated;
    }

    /**
     * @param float|null $total_exonerated
     *
     * @return SubscriptionPlan
     */
    public function setTotalExonerated(?float $total_exonerated): SubscriptionPlan
    {
        $this->total_exonerated = $total_exonerated;
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
     * @return SubscriptionPlan
     */
    public function setTotalIgv(?float $total_igv): SubscriptionPlan
    {
        $this->total_igv = $total_igv;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalIgvFree(): ?float
    {
        return $this->total_igv_free;
    }

    /**
     * @param float|null $total_igv_free
     *
     * @return SubscriptionPlan
     */
    public function setTotalIgvFree(?float $total_igv_free): SubscriptionPlan
    {
        $this->total_igv_free = $total_igv_free;
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
     * @return SubscriptionPlan
     */
    public function setTotalBaseIsc(?float $total_base_isc): SubscriptionPlan
    {
        $this->total_base_isc = $total_base_isc;
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
     * @return SubscriptionPlan
     */
    public function setTotalIsc(?float $total_isc): SubscriptionPlan
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
     * @return SubscriptionPlan
     */
    public function setTotalBaseOtherTaxes(?float $total_base_other_taxes): SubscriptionPlan
    {
        $this->total_base_other_taxes = $total_base_other_taxes;
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
     * @return SubscriptionPlan
     */
    public function setTotalOtherTaxes(?float $total_other_taxes): SubscriptionPlan
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
     * @return SubscriptionPlan
     */
    public function setTotalTaxes(?float $total_taxes): SubscriptionPlan
    {
        $this->total_taxes = $total_taxes;
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
     * @return SubscriptionPlan
     */
    public function setTotalValue(?float $total_value): SubscriptionPlan
    {
        $this->total_value = $total_value;
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
     * @return SubscriptionPlan
     */
    public function setCharges(?string $charges): SubscriptionPlan
    {
        $this->charges = $charges;
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
     * @return SubscriptionPlan
     */
    public function setDiscounts(?string $discounts): SubscriptionPlan
    {
        $this->discounts = $discounts;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrepayments(): ?string
    {
        return $this->prepayments;
    }

    /**
     * @param string|null $prepayments
     *
     * @return SubscriptionPlan
     */
    public function setPrepayments(?string $prepayments): SubscriptionPlan
    {
        $this->prepayments = $prepayments;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRelated(): ?string
    {
        return $this->related;
    }

    /**
     * @param string|null $related
     *
     * @return SubscriptionPlan
     */
    public function setRelated(?string $related): SubscriptionPlan
    {
        $this->related = $related;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerception(): ?string
    {
        return $this->perception;
    }

    /**
     * @param string|null $perception
     *
     * @return SubscriptionPlan
     */
    public function setPerception(?string $perception): SubscriptionPlan
    {
        $this->perception = $perception;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDetraction(): ?string
    {
        return $this->detraction;
    }

    /**
     * @param string|null $detraction
     *
     * @return SubscriptionPlan
     */
    public function setDetraction(?string $detraction): SubscriptionPlan
    {
        $this->detraction = $detraction;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLegends(): ?string
    {
        return $this->legends;
    }

    /**
     * @param string|null $legends
     *
     * @return SubscriptionPlan
     */
    public function setLegends(?string $legends): SubscriptionPlan
    {
        $this->legends = $legends;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTermsCondition(): ?string
    {
        return $this->terms_condition;
    }

    /**
     * @param string|null $terms_condition
     *
     * @return SubscriptionPlan
     */
    public function setTermsCondition(?string $terms_condition): SubscriptionPlan
    {
        $this->terms_condition = $terms_condition;
        return $this;
    }

}
