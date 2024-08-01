<?php

namespace Modules\FullSubscription\Models;

use App\Models\Tenant\ModelTenant;

class FullSubscriptionUserDatum extends ModelTenant
{
    protected $perPage = 25;

    protected $fillable = [
        'person_id',
        'discord_user',
        'slack_channel',
        'discord_channel',
        'gitlab_user'
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

    public function setPersonId(?int $person_id): FullSubscriptionUserDatum
    {
        $this->person_id = $person_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDiscordUser(): ?string
    {
        return $this->discord_user;
    }

    public function setDiscordUser(?string $discord_user): FullSubscriptionUserDatum
    {
        $this->discord_user = $discord_user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlackChannel(): ?string
    {
        return $this->slack_channel;
    }

    /**
     * @param string|null $slack_channel
     *
     * @return FullSubscriptionUserDatum
     */
    public function setSlackChannel(?string $slack_channel): FullSubscriptionUserDatum
    {
        $this->slack_channel = $slack_channel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDiscordChannel(): ?string
    {
        return $this->discord_channel;
    }

    public function setDiscordChannel(?string $discord_channel): FullSubscriptionUserDatum
    {
        $this->discord_channel = $discord_channel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGitlabUser(): ?string
    {
        return $this->gitlab_user;
    }

    public function setGitlabUser(?string $gitlab_user): FullSubscriptionUserDatum
    {
        $this->gitlab_user = $gitlab_user;
        return $this;
    }

}
