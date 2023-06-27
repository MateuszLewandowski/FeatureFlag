<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Specification\Predicates;

use FeatureFlag\Access\Domain\Exception\InvalidUserRoleException;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\User;

final class IsUserRoleAvailable implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->role?->value) {
            throw new InvalidUserRoleException();
        }

        return $featureFlag->config->userRoles->exists($user->role->value);
    }
}
