<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Specification\Predicates;

use FeatureFlag\Access\Application\Exception\UserEmailNotFoundException;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;

final class DoesUserEmailAddressIncludesDomain implements UserExtendedExpressible
{
    public function execute(FeatureFlag $featureFlag, User $user): bool
    {
        if (null === $user->email?->value) {
            throw new UserEmailNotFoundException();
        }

        return $featureFlag->config->userEmailDomainNames->exists(
            UserEmailDomainName::createWithUser($user)->value
        );
    }
}
