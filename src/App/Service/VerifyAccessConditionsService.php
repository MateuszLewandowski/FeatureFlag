<?php

declare(strict_types=1);

namespace App\Service;

use FeatureFlag\Access\Application\VerifyAccessRules;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Service\FindService;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagNotFoundException;

final class VerifyAccessConditionsService
{
    public function __construct(
        private readonly VerifyAccessRules $verifier,
        private readonly FindService $service,
    ) {}
    
    /** @throws FeatureFlagNotFoundException */
    public function isFeatureAvailable(FeatureFlagId $featureFlagId, User $user): bool
    {
        $featureFlag = $this->service->find($featureFlagId);
        
        return $this->verifier->verify($featureFlag, $user);
    }
}
