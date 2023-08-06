<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Exception\UserIdNotFoundException;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\Entity\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\Entity\User
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Application\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\Exception\UserIdNotFoundException
 */
final class IsUserIdAvailableTest extends TestCase
{
    public function testExpectsTrue(): void
    {
        $expression = new IsUserIdAvailable();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserIds([1])
                    ->build()
            ),
            new User(new UserId(1), null, null)
        );

        $this->assertTrue($result);
    }

    public function testExpectsFalse(): void
    {
        $expression = new IsUserIdAvailable();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserIds([1])
                    ->build()
            ),
            new User(new UserId(2), null, null)
        );

        $this->assertFalse($result);
    }

    public function testExpectsException(): void
    {
        $this->expectException(UserIdNotFoundException::class);

        $expression = new IsUserIdAvailable();
        $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserIds([1])
                    ->build()
            ),
            new User(null, null, null)
        );
    }
}
