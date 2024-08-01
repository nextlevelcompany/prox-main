<?php

namespace Modules\Hotel\Models;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Builder;
use Modules\Person\Models\Person;

class HotelRent extends ModelTenant
{
    protected $table = 'hotel_rents';

    protected $fillable = [
        'customer_id',
        'customer',
        'notes',
        'towels',
        'hotel_room_id',
        'hotel_rate_id',
        'duration',
        'quantity_persons',
        'payment_status',
        'output_date',
        'output_time',
        'input_date',
        'input_time',
        'arrears',
        'status'
    ];

    protected $casts = [
        'customer_id' => 'int',
        'towels' => 'int',
        'hotel_room_id' => 'int',
        'duration' => 'int',
        'quantity_persons' => 'int',
        'arrears' => 'int'
    ];

    public function getCustomerAttribute($value)
    {
        return (is_null($value)) ? null : (object)json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }

    public function rate()
    {
        return $this->belongsTo(HotelRate::class, 'hotel_rate_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(HotelRentItem::class, 'hotel_rent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(HotelRentItem::class, 'hotel_rent_id')->where('type', 'PRO');
    }


    /**
     * @param Builder $query
     * @param null $date_start
     * @param null $date_end
     *
     * @return Builder
     */
    public function scopeSearchByDate(Builder $query, $date_start, $date_end)
    {

        if ($date_start) {
            $query->where('input_date', '>=', $date_start);
        }
        if ($date_end) {
            $query->where('output_date', '<=', $date_end);
        }

        return $query;
    }

    public function searchPersonDetails($value)
    {
        $id = '';

        if ($value->customer->id) {
            $id = $value->customer->id;
            $data = Person::with('identity_document_type', 'country')
                ->orderBy('id', 'DESC');
            return $data = $data->where('id', $id)->get();
        }
    }

    public function searchPersonNationality($value)
    {
        $id = '';

        if ($value->customer->id) {
            $id = $value->customer->id;
            $data = Person::with('nationality')
                ->orderBy('id', 'DESC');
            return $data = $data->where('id', $id)->get();
        }
    }

    public function searchRateRoom($value)
    {
        $id = '';
        $room_id = '';
        if ($value->rate) {
            if ($value->rate->id) {
                $id = $value->rate->id;
                $room_id = $value->hotel_room_id;
                return $data = HotelRoomRate::where('hotel_rate_id', $id)->where('hotel_room_id', $room_id)->get();
            }

        }
    }


    /**
     * Retorna moneda nacional por defecto
     *
     * @TODO considerar registro de moneda al rentar habitacion
     *
     * @return string
     */
    public function getDefaultCurrency()
    {
        return self::NATIONAL_CURRENCY_ID;
    }

}
