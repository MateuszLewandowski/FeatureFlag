<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\DTO;

final class FeatureFlagDTO
{
    public function __construct(
        public readonly string $featureFlagId,
        public readonly ?bool $forceGrantAccess,
        public readonly ?string $startsAt,
        public readonly ?string $endsAt,
        public readonly ?array $userEmailDomainNames,
        public readonly ?array $userIds,
        public readonly ?array $userRoles,
        public readonly ?int $moduloUserId,
    ) {}
}
