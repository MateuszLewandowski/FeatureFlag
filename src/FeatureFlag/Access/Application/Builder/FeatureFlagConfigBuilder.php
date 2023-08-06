<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Builder;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Collection\UserEmailDomainNameCollection;
use FeatureFlag\Access\Application\Collection\UserIdCollection;
use FeatureFlag\Access\Application\Collection\UserRoleCollection;
use FeatureFlag\Access\Domain\ValueObject\EndsAt;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\ModuloUserId;
use FeatureFlag\Access\Domain\ValueObject\StartsAt;

final class FeatureFlagConfigBuilder
{
    private ?bool $forceGrantAccess = false;
    private ?UserEmailDomainNameCollection $userEmailDomainNames = null;
    private ?UserIdCollection $userIds = null;
    private ?UserRoleCollection $userRoles = null;
    private ?ModuloUserId $moduloUserId = null;
    private ?StartsAt $startsAt = null;
    private ?EndsAt $endsAt = null;

    public static function create(): self
    {
        return new self();
    }

    public function setForceGrantAccess(?bool $forceGrantAccess): self
    {
        if (null === $forceGrantAccess) {
            return $this;
        }

        $this->forceGrantAccess = $forceGrantAccess;

        return $this;
    }

    public function setEndsAt(?string $endsAt): self
    {
        if (null === $endsAt) {
            return $this;
        }

        $this->endsAt = new EndsAt(new DateTimeImmutable($endsAt), $this->startsAt ?: null);

        return $this;
    }

    public function setStartsAt(?string $startsAt): self
    {
        if (null === $startsAt) {
            return $this;
        }

        $this->startsAt = new StartsAt(new DateTimeImmutable($startsAt));

        return $this;
    }

    public function setUserEmailDomainNames(?array $userEmailDomainNames): self
    {
        $this->userEmailDomainNames = $userEmailDomainNames
            ? new UserEmailDomainNameCollection($userEmailDomainNames)
            : null;

        return $this;
    }

    public function setUserIds(?array $userIds): self
    {
        $this->userIds = $userIds
            ? new UserIdCollection($userIds)
            : null;

        return $this;
    }

    public function setUserRoles(?array $userRoles): self
    {
        $this->userRoles = $userRoles
            ? new UserRoleCollection($userRoles)
            : null;

        return $this;
    }

    public function setModuloUserId(?int $moduloUserId): self
    {
        if (null === $moduloUserId) {
            return $this;
        }

        $this->moduloUserId = new ModuloUserId($moduloUserId);

        return $this;
    }

    public function build(): FeatureFlagConfig
    {
        return new FeatureFlagConfig(
            $this->forceGrantAccess,
            $this->startsAt,
            $this->endsAt,
            $this->userEmailDomainNames,
            $this->userIds,
            $this->userRoles,
            $this->moduloUserId,
        );
    }
}
