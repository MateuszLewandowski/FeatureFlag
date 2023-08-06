<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Specification\Predicates\IsStartsAtDateExceeded;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsStartsAtDateExceeded
 * @covers \FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder
 * @covers \FeatureFlag\Access\Domain\Entity\FeatureFlag
 * @covers \FeatureFlag\Access\Domain\ValueObject\StartsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagId
 */
final class IsStartsAtDateExceededTest extends TestCase
{
    public function testExpectsTrue(): void
    {
        $expression = new IsStartsAtDateExceeded();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setStartsAt((new DateTimeImmutable('yesterday'))->format('Y-m-d H:i:s'))
                    ->build(),
            ),
        );

        $this->assertTrue($result);
    }

    public function testExpectsFalse(): void
    {
        $expression = new IsStartsAtDateExceeded();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setStartsAt((new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'))
                    ->build()
            ),
        );

        $this->assertFalse($result);
    }
}
