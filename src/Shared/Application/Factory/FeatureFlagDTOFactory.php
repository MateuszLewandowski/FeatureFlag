<?php

declare(strict_types=1);

namespace Shared\Application\Factory;

use FeatureFlag\Access\Application\DTO\FeatureFlagDTO;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;

final class FeatureFlagDTOFactory
{
    public static function create(FeatureFlag $featureFlag): FeatureFlagDTO
    {
        return new FeatureFlagDTO(
            $featureFlag->id->value,
            $featureFlag->config->forceGrantAccess,
            $featureFlag->config->startsAt?->value?->format('Y-m-d h:i:s'),
            $featureFlag->config->endsAt?->value?->format('Y-m-d h:i:s'),
            $featureFlag->config->userEmailDomainNames->toArray(),
            $featureFlag->config->userIds->toArray(),
            $featureFlag->config->userRoles->toArray(),
            $featureFlag->config->moduloUserId->value,
        );
    }
}
