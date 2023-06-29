<?php

declare(strict_types=1);

namespace App\Tests\Access\Infrastructure;

use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagAlreadyExistsException;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagNotFoundException;
use FeatureFlag\Access\Infrastructure\Exception\JsonFileNotFoundException;
use FeatureFlag\Access\Infrastructure\Persistence\FeatureFlagJsonFileRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers \FeatureFlag\Access\Infrastructure\Persistence\FeatureFlagJsonFileRepository
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\FeatureFlag
 * @covers \FeatureFlag\Access\Infrastructure\Exception\FeatureFlagAlreadyExistsException
 * @covers \FeatureFlag\Access\Infrastructure\Exception\JsonFileNotFoundException
 * @covers \FeatureFlag\Access\Infrastructure\Exception\FeatureFlagNotFoundException
 */
final class FeatureFlagJsonFileRepositoryTest extends TestCase
{
    private const PATH = __DIR__ . '/../../../src/test.feature-flags.json';

    public function testTogglesJsonFileExists(): void
    {
        $repository = new FeatureFlagJsonFileRepository(self::PATH);

        $featureToggles = $repository->getFeatureFlags();

        $this->assertIsArray($featureToggles);
        $this->assertEmpty($featureToggles);
    }

    public function testToggleJsonFileDoesNotExists(): void
    {
        $this->expectException(JsonFileNotFoundException::class);

        new FeatureFlagJsonFileRepository('invalid/path/to/file.json');
    }

    public function testGetNonExistingKey(): void
    {
        $this->expectException(RuntimeException::class);

        $repository = new FeatureFlagJsonFileRepository(self::PATH);

        $repository->get(new FeatureFlagId('non-existing key'));
    }

    public function testSetWhenFeatureFlagAlreadyExists(): void
    {
        $this->expectException(FeatureFlagAlreadyExistsException::class);
        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key52'),
            FeatureFlagConfigBuilder::create()->build()
        );

        $repository = new FeatureFlagJsonFileRepository(self::PATH);
        $repository->set($featureFlag);
        $repository->set($featureFlag);
    }

    public function testSetFeatureFlagAndStoreAsInMemoryArray(): void
    {
        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key52'),
            FeatureFlagConfigBuilder::create()->build()
        );

        $repository = new FeatureFlagJsonFileRepository(self::PATH);

        $repository->set($featureFlag);
        $newFeatureToggles = $repository->getFeatureFlags();
        $repository->clean();

        $this->assertNotEmpty($newFeatureToggles);
        $this->assertContainsOnlyInstancesOf(FeatureFlag::class, $newFeatureToggles);
    }

    public function testCreateWithSave(): void
    {
        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key 11'),
            FeatureFlagConfigBuilder::create()->build()
        );

        $repository = new FeatureFlagJsonFileRepository(self::PATH);

        $repository->set($featureFlag)->save();
        $featureFlags = $repository->getFeatureFlags();

        $repository = new FeatureFlagJsonFileRepository(self::PATH);

        $newFeatureFlags = $repository->getFeatureFlags();

        $this->assertEquals($featureFlags, $newFeatureFlags);

        $repository->clean()->save();
    }

    public function testGet(): void
    {
        $featureFlag = new FeatureFlag(
            new FeatureFlagId('Key78'),
            FeatureFlagConfigBuilder::create()->build()
        );

        $repository = new FeatureFlagJsonFileRepository(self::PATH);

        $repository->set($featureFlag);
        $restoredFeatureFlag = $repository->get($featureFlag->id);
        $repository->clean();

        $this->assertSame($featureFlag, $restoredFeatureFlag);
    }

    public function testUpdate(): void
    {
        $featureFlagId = new FeatureFlagId('Key');
        $featureFlagConfig = FeatureFlagConfigBuilder::create()
            ->setUserIds([1, 3, 5])
            ->build();

        $featureFlag = new FeatureFlag($featureFlagId, $featureFlagConfig);

        $repository = new FeatureFlagJsonFileRepository(self::PATH);
        $repository->set($featureFlag);

        $newFeatureFlagConfig = FeatureFlagConfigBuilder::create()
            ->setUserIds([1, 7, 13])
            ->build();

        $repository->update($featureFlagId, $newFeatureFlagConfig);

        $storedNewFeatureFlag = $repository->get($featureFlagId)->config->userIds->toArray();
        $repository->clean();

        $this->assertSame($storedNewFeatureFlag, $newFeatureFlagConfig->userIds->toArray());
    }

    public function testDelete(): void
    {
        $featureFlag = new FeatureFlag(
            new FeatureFlagId('DeleteFlag'),
            FeatureFlagConfigBuilder::create()->build()
        );

        $repository = new FeatureFlagJsonFileRepository(self::PATH);
        $repository->set($featureFlag);
        $featureFlag = $repository->get($featureFlag->id);
        $repository->delete($featureFlag->id);

        $this->expectException(FeatureFlagNotFoundException::class);

        $repository->get($featureFlag->id);
    }

    public function testDeleteExpectsFeatureNotFoundException(): void
    {
        $this->expectException(FeatureFlagNotFoundException::class);

        $repository = new FeatureFlagJsonFileRepository(self::PATH);
        $repository->delete(new FeatureFlagId('NotExistsDeleteFlag'));
    }

    public function testUpdateExpectsFeatureNotFoundException(): void
    {
        $this->expectException(FeatureFlagNotFoundException::class);

        $repository = new FeatureFlagJsonFileRepository(self::PATH);
        $repository->update(
            new FeatureFlagId('NotExistsDeleteFlag'),
            FeatureFlagConfigBuilder::create()->build()
        );
    }
}
