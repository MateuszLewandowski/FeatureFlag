<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Domain\Collection\UserEmailDomainNameCollection;
use FeatureFlag\Access\Domain\Collection\UserIdCollection;
use FeatureFlag\Access\Domain\Collection\UserRoleCollection;
use JsonSerializable;
use Shared\ValueObject;

final class FeatureFlagConfig implements JsonSerializable, ValueObject
{
    public function __construct(
        public readonly ?bool $forceGrantAccess,
        public readonly ?StartsAt $startsAt,
        public readonly ?EndsAt $endsAt,
        public readonly ?UserEmailDomainNameCollection $userEmailDomainNames,
        public readonly ?UserIdCollection $userIds,
        public readonly ?UserRoleCollection $userRoles,
        public readonly ?ModuloUserId $moduloUserId,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'forceGrantAccess' => $this->forceGrantAccess,
            'startsAt' => $this->startsAt?->jsonSerialize(),
            'endsAt' => $this->endsAt?->jsonSerialize(),
            'moduloUserId' => $this?->moduloUserId?->value,
            'userRoles' => $this->userRoles?->toArray(),
            'userEmailDomainNames' => $this->userEmailDomainNames?->toArray(),
            'userIds' => $this->userIds?->toArray(),
        ];
    }
    
    public function databaseSerialize(): array
    {
        return [
            'force_grant_access' => $this->forceGrantAccess ? 1 : 0,
            'starts_at' => $this->startsAt?->jsonSerialize(),
            'ends_at' => $this->endsAt?->jsonSerialize(),
            'modulo_user_id' => $this?->moduloUserId?->value,
            'user_roles' => json_encode($this->userRoles?->toArray()),
            'user_email_domain_names' => json_encode($this->userEmailDomainNames?->toArray()),
            'user_ids' => json_encode($this->userIds?->toArray()),
        ];
    }
}
