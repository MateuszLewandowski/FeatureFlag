<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Infrastructure\Persistence;

use Exception;
use FeatureFlag\Access\Application\FeatureFlagRepository;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagAlreadyExistsException;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagNotFoundException;
use FeatureFlag\Access\Infrastructure\Exception\JsonFileNotFoundException;

final class FeatureFlagJsonFileRepository implements FeatureFlagRepository
{
    private array $featureFlags = [];

    public function __construct(private readonly string $path)
    {
        if (!file_exists($path)) {
            throw new JsonFileNotFoundException();
        }

        $featureFlags = json_decode(file_get_contents($path), true) ?? [];

        foreach ($featureFlags as $key => $config) {
            $this->featureFlags[$key] = new FeatureFlag(
                new FeatureFlagId($key),
                FeatureFlagConfigBuilder::create()
                    ->setForceGrantAccess($config['forceGrantAccess'])
                    ->setDateThreshold($config['dateThreshold'])
                    ->setUserEmailDomainNames($config['userEmailDomainNames'])
                    ->setUserIds($config['userIds'])
                    ->setUserRoles($config['userRoles'])
                    ->setModuloUserId($config['moduloUserId'])
                    ->build()
            );
        }
    }

    /** @return FeatureFlag[] */
    public function getFeatureFlags(): array
    {
        return $this->featureFlags;
    }

    public function get(FeatureFlagId $id): FeatureFlag
    {
        if (!isset($this->featureFlags[$id->value])) {
            throw new FeatureFlagNotFoundException($id);
        }

        return $this->featureFlags[$id->value];
    }

    public function set(FeatureFlag $featureFlag): self
    {
        if (isset($this->featureFlags[$featureFlag->id->value])) {
            throw new FeatureFlagAlreadyExistsException($featureFlag);
        }

        $this->featureFlags[$featureFlag->id->value] = $featureFlag;

        return $this;
    }

    public function delete(FeatureFlagId $id): self
    {
        if (!isset($this->featureFlags[$id->value])) {
            throw new FeatureFlagNotFoundException($id);
        }

        unset($this->featureFlags[$id->value]);

        return $this;
    }

    public function update(FeatureFlagId $id, FeatureFlagConfig $config): self
    {
        if (!isset($this->featureFlags[$id->value])) {
            throw new FeatureFlagNotFoundException($id);
        }

        $this->featureFlags[$id->value] = new FeatureFlag($id, $config);

        return $this;
    }

    public function clean(): self
    {
        $this->featureFlags = [];

        return $this;
    }

    public function save(): self
    {
        /** @var FeatureFlag $featureFlag */
        foreach ($this->featureFlags as $featureFlag) {
            $featureFlags[$featureFlag->id->value] = $featureFlag->config->jsonSerialize();
        }

        file_put_contents($this->path, json_encode($featureFlags ?? []));

        return $this;
    }
}
