<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Domain\Factory;

use DateTimeImmutable;
use DateTimeZone;
use FeatureFlag\Access\Domain\Collection\UserEmailDomainNameCollection;
use FeatureFlag\Access\Domain\Collection\UserIdCollection;
use FeatureFlag\Access\Domain\Collection\UserRoleCollection;
use FeatureFlag\Access\Domain\Exception\InvalidDateThresholdTimeZoneException;
use FeatureFlag\Access\Domain\ValueObject\DateThreshold;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\ModuloUserId;
use Throwable;

final class FeatureFlagConfigBuilder
{
    public ?UserEmailDomainNameCollection $userEmailDomainNames = null;
    public ?UserIdCollection $userIds = null;
    public ?UserRoleCollection $userRoles = null;
    public ?ModuloUserId $moduloUserId = null;
    private ?bool $forceGrantAccess = false;
    private ?DateThreshold $dateThreshold = null;

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

    public function setDateThreshold(?array $dateThreshold): self
    {
        if (null === $dateThreshold) {
            return $this;
        }

        if (!isset($dateThreshold['date']) or !isset($dateThreshold['timeZone'])) {
            return $this;
        }

        try {
            $this->dateThreshold = new DateThreshold(
                new DateTimeImmutable($dateThreshold['date'], new DateTimeZone($dateThreshold['timeZone']))
            );

            return $this;
        } catch (Throwable $e) {
            throw new InvalidDateThresholdTimeZoneException($e->getMessage());
        }
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
            $this->dateThreshold,
            $this->userEmailDomainNames,
            $this->userIds,
            $this->userRoles,
            $this->moduloUserId,
        );
    }
}
