<?php

namespace Modules\System\Models;

use App\Models\ModelSystem;
use Hyn\Tenancy\Models\Hostname;
use Illuminate\Support\Facades\Config;

class Client extends ModelSystem
{
    protected $fillable = [
        'hostname_id',
        'number',
        'name',
        'email',
        'token',
        'locked',
        'locked_emission',
        'locked_tenant',
        'locked_users',
        'plan_id',
        'start_billing_cycle',
        'smtp_host',
        'smtp_port',
        'smtp_user',
        'smtp_password',
        'smtp_encryption',
        'locked_create_establishments',
        'restrict_sales_limit',
    ];


    protected $casts = [
        'start_billing_cycle' => 'date',
        'smtp_port' => 'int',
        'locked_create_establishments' => 'boolean',
        'restrict_sales_limit' => 'boolean',
    ];


    /**
     * @return mixed
     */
    public function getSmtpHost()
    {
        return empty($this->smtp_host)?Config::get('mail.host'):$this->smtp_host;
    }

    /**
     * @param mixed $smtp_host
     *
     * @return Client
     */
    public function setSmtpHost($smtp_host)
    {
        $this->smtp_host = $smtp_host;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSmtpPort()
    {
        if($this->smtp_port == 0)$this->smtp_port = null;
        return empty($this->smtp_port)?Config::get('mail.port'):$this->smtp_port;
    }

    /**
     * @param mixed $smtp_port
     *
     * @return Client
     */
    public function setSmtpPort($smtp_port)
    {
        $this->smtp_port = (int) trim($smtp_port);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSmtpUser()
    {
        return empty($this->smtp_user)?Config::get('mail.username'):$this->smtp_user;
    }

    /**
     * @param mixed $smtp_user
     *
     * @return Client
     */
    public function setSmtpUser($smtp_user)
    {
        $this->smtp_user = trim($smtp_user);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSmtpPassword()
    {
        return empty($this->smtp_password)?Config::get('mail.password'):$this->smtp_password;
    }

    /**
     * @param mixed $smtp_password
     *
     * @return Client
     */
    public function setSmtpPassword($smtp_password)
    {
        $this->smtp_password = trim($smtp_password);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSmtpEncryption()
    {
        return empty($this->smtp_encryption)?Config::get('mail.encryption'):$this->smtp_encryption;
    }

    /**
     * @param mixed $smtp_encryption
     *
     * @return Client
     */
    public function setSmtpEncryption($smtp_encryption)
    {
        $this->smtp_encryption = strtolower(trim($smtp_encryption));
        return $this;
    }

    public function hostname()
    {
        return $this->belongsTo(Hostname::class)->with(['website']);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(ClientPayment::class);
    }


    /**
     *
     * Filtro para no incluir relaciones en consulta
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWhereFilterWithOutRelations($query)
    {
        return $query->withOut(['hostname','plan']);
    }


}
