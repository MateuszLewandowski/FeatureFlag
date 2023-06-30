<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Domain\Collection\UserEmailDomainNameCollection;
use FeatureFlag\Access\Domain\Collection\UserIdCollection;
use FeatureFlag\Access\Domain\Collection\UserRoleCollection;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\RequestCreatable;
use JsonSerializable;
use Shared\ValueObject;
use Symfony\Component\HttpFoundation\Request;

final class FeatureFlagConfig implements RequestCreatable, JsonSerializable, ValueObject
{
    public function __construct(
        public readonly ?bool $forceGrantAccess,
        public readonly ?DateThreshold $dateThreshold,
        public readonly ?UserEmailDomainNameCollection $userEmailDomainNames,
        public readonly ?UserIdCollection $userIds,
        public readonly ?UserRoleCollection $userRoles,
        public readonly ?ModuloUserId $moduloUserId,
    ) {}

    public static function createWithRequest(Request $request): self
    {
        return FeatureFlagConfigBuilder::create()
            ->setForceGrantAccess($request->get('forceGrantAccess', false))
            ->setDateThreshold(json_decode($request->get('dateThreshold', '') ?? null, true))
            ->setUserEmailDomainNames(json_decode($request->request->getString('userEmailDomainNames', '[]'), true))
            ->setUserIds(json_decode($request->request->getString('userIds', '[]'), true))
            ->setUserRoles(json_decode($request->request->getString('userRoles', '[]'), true))
            ->setModuloUserId($request->get('moduloUserId'))
            ->build();
    }

    public function jsonSerialize(): array
    {
        return [
            'forceGrantAccess' => $this->forceGrantAccess,
            'dateThreshold' => $this->dateThreshold?->jsonSerialize(),
            'moduloUserId' => $this?->moduloUserId?->value,
            'userRoles' => $this->userRoles?->toArray(),
            'userEmailDomainNames' => $this->userEmailDomainNames?->toArray(),
            'userIds' => $this->userIds?->toArray(),
        ];
    }
}
