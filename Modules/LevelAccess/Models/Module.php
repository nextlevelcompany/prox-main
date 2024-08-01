<?php

namespace Modules\LevelAccess\Models;

use App\Models\Tenant\ModelTenant;
use Modules\User\Models\User;

class Module extends ModelTenant
{
    protected $fillable = [
        'value',
        'order_menu',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function levels()
    {
        return $this->hasMany(ModuleLevel::class);
    }

    /**
     * @return string
     */
    public function getDescription()
    : string {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Module
     */
    public function setDescription(string $description)
    : Module {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    : string {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return Module
     */
    public function setValue(string $value)
    : Module {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderMenu()
    : int {
        return $this->order_menu;
    }

    /**
     * @param int $order_menu
     *
     * @return Module
     */
    public function setOrderMenu(int $order_menu)
    : Module {
        $this->order_menu = $order_menu;
        return $this;
    }

    /**
     * @return $this
     */
    public function setLastOrderMenuInt(){
        $this->setOrderMenu(self::where('id','!=',$this->id)->select('order_menu')->max('order_menu'));
        return $this;
    }
}
