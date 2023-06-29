<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Collection;

use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;

final class UserEmailDomainNameCollection extends ValueObjectCollection
{
    public function __construct(array $userEmailDomainNames)
    {
        parent::__construct(self::createFromPrimitives(UserEmailDomainName::class, $userEmailDomainNames));
    }
}
