<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Factory;

use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\UserEmail;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;

final class UserBuilder
{
    private ?UserId $userId = null;
    private ?UserRole $userRole = null;
    private ?UserEmail $userEmail = null;

    public static function create(): self
    {
        return new self();
    }

    public function setId(?int $id): self
    {
        if (null === $id) {
            return $this;
        }

        $this->userId = new UserId($id);

        return $this;
    }

    public function setRole(?int $role): self
    {
        if (null === $role) {
            return $this;
        }

        $this->userRole = new UserRole($role);

        return $this;
    }

    public function setEmail(?string $email): self
    {
        if (null === $email) {
            return $this;
        }

        $this->userEmail = new UserEmail($email);

        return $this;
    }

    public function build(): User
    {
        return new User(
            $this->userId,
            $this->userRole,
            $this->userEmail
        );
    }
}
