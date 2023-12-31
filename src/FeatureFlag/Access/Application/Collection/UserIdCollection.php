<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Collection;

use FeatureFlag\Access\Domain\ValueObject\UserId;

final class UserIdCollection extends ValueObjectCollection
{
    public function __construct(array $userIds)
    {
        parent::__construct(self::createFromPrimitives(UserId::class, $userIds));
    }
}
