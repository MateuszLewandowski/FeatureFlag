<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Application\Exception\UserRoleNotFoundException;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;

final class IsUserRoleAvailable implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->role?->value) {
            throw new UserRoleNotFoundException();
        }

        return $featureFlag->config->userRoles->exists($user->role->value);
    }
}
