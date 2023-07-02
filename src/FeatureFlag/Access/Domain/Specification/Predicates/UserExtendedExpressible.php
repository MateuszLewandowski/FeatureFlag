<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;

interface UserExtendedExpressible extends Expressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool;
}
