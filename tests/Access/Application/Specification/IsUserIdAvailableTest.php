<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use FeatureFlag\Access\Domain\Exception\InvalidUserIdException;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Exception\invalidUserRoleException
 * @covers \FeatureFlag\Access\Domain\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Domain\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\User
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 * @covers \FeatureFlag\Access\Domain\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
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
        $this->expectException(invalidUserIdException::class);

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
