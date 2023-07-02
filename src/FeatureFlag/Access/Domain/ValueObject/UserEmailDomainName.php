<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\ValueObject;

use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Exception\InvalidUserEmailDomainNameException;
use FeatureFlag\Access\Domain\Exception\UserEmailNotFoundException;
use Shared\ValueObject;

final class UserEmailDomainName implements ValueObject
{
    public function __construct(
        public readonly string $value,
    ) {
        if (!preg_match('/[a-z0-9]{2,255}[.][a-z]{2,4}/', $value)) {
            throw new InvalidUserEmailDomainNameException($value);
        }
    }

    public static function createWithUser(User $user): self
    {
        $email = $user->email?->value;

        if (null === $email) {
            throw new UserEmailNotFoundException();
        }

        $parts = explode('@', $email);
        $domainName = array_pop($parts);

        return new self($domainName);
    }
}
