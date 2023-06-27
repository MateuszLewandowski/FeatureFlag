<?php

declare(strict_types=1);

namespace Shared\Application\Factory;

use FeatureFlag\Access\Application\DTO\FeatureFlagDTO;
use FeatureFlag\Access\Domain\FeatureFlag;

final class FeatureFlagDTOFactory
{
    public static function create(FeatureFlag $featureFlag): FeatureFlagDTO
    {
        return new FeatureFlagDTO(
            $featureFlag->id->value,
            $featureFlag->config->forceGrantAccess,
            $featureFlag->config->userEmailDomainNames->toArray(),
            $featureFlag->config->userIds->toArray(),
            $featureFlag->config->userRoles->toArray(),
            $featureFlag->config->moduloUserId->value,
        );
    }
}
