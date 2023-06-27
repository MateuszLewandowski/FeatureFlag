<?php

declare(strict_types=1);

namespace App\Tests\Access\Application\Specification;

use FeatureFlag\Access\Application\Specification\Predicates\DoesUserEmailAddressIncludesDomain;
use FeatureFlag\Access\Domain\Exception\InvalidUserEmailDomainNameException;
use FeatureFlag\Access\Domain\Exception\InvalidUserEmailException;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Domain\ValueObject\UserEmail;
use PHPUnit\Framework\TestCase;

/**
 * @covers DoesUserEmailAddressIncludesDomain
 * @covers InvalidUserEmailDomainNameException
 */
final class DoesUserEmailAddressIncludesDomainTest extends TestCase
{
    public function testExpectsTrue(): void
    {
        $expression = new DoesUserEmailAddressIncludesDomain();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserEmailDomainNames(['gmail.com'])
                    ->build()
            ),
            new User(null, null, new UserEmail('user@gmail.com'))
        );

        $this->assertTrue($result);
    }

    public function testExpectsFalse(): void
    {
        $expression = new DoesUserEmailAddressIncludesDomain();
        $result = $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserEmailDomainNames(['gmail.com'])
                    ->build()
            ),
            new User(null, null, new UserEmail('user@wp.pl'))
        );

        $this->assertFalse($result);
    }

    public function testExpectsException(): void
    {
        $this->expectException(InvalidUserEmailException::class);

        $expression = new DoesUserEmailAddressIncludesDomain();
        $expression->execute(
            new FeatureFlag(
                new FeatureFlagId('Simply'),
                FeatureFlagConfigBuilder::create()
                    ->setUserEmailDomainNames(['gmail.com'])
                    ->build()
            ),
            new User(null, null, null)
        );
    }
}
