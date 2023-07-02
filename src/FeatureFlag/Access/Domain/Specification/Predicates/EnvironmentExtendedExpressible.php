<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Domain\Entity\FeatureFlag;

interface EnvironmentExtendedExpressible extends Expressible
{
    public function execute(FeatureFlag $featureFlag): bool;
}
