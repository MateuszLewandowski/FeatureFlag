<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Exception\UserIdNotFoundException;

final class DoesUserIdSatisfyModulo implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->id?->value) {
            throw new UserIdNotFoundException();
        }

        return 0 === $user->id->value % $featureFlag->config->moduloUserId->value;
    }
}
