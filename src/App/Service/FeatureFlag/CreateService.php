<?php

declare(strict_types=1);

namespace App\Service\FeatureFlag;

use FeatureFlag\Access\Application\SettableRepository;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;

final class CreateService
{
    public function __construct(
        private readonly SettableRepository $repository
    ) {
    }
    
    public function create(FeatureFlag $featureFlag): void
    {
        $this->repository->set($featureFlag);
    }
}
