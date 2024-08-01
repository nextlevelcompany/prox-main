<?php

namespace Modules\FullSubscription\Models;

use App\Models\Tenant\ModelTenant;

class FullSubscriptionServerDatum extends ModelTenant
{
    protected $perPage = 25;

    protected $fillable = [
        'person_id',
        'host',
        'ip',
        'user',
        'password'
    ];

    protected $casts = [
        'person_id' => 'int'
    ];

    /**
     * @return int|null
     */
    public function getPersonId(): ?int
    {
        return $this->person_id;
    }

    public function setPersonId(?int $person_id): FullSubscriptionServerDatum
    {
        $this->person_id = $person_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): FullSubscriptionServerDatum
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): FullSubscriptionServerDatum
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): FullSubscriptionServerDatum
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): FullSubscriptionServerDatum
    {
        $this->password = $password;
        return $this;
    }
}
