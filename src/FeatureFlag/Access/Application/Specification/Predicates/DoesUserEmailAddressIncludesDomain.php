<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Specification\Predicates;

use FeatureFlag\Access\Domain\Exception\InvalidUserEmailException;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;

final class DoesUserEmailAddressIncludesDomain implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->email?->value) {
            throw new InvalidUserEmailException();
        }

        return $featureFlag->config->userEmailDomainNames->exists(
            UserEmailDomainName::createWithUser($user)->value
        );
    }
}
