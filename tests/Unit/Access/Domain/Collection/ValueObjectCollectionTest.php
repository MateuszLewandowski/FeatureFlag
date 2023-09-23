<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain\Collection;

use FeatureFlag\Access\Application\Collection\UserEmailDomainNameCollection;
use FeatureFlag\Access\Application\Collection\UserIdCollection;
use FeatureFlag\Access\Application\Collection\UserRoleCollection;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\Collection\UserIdCollection
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Application\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Application\Collection\UserRoleCollection
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Application\Collection\UserEmailDomainNameCollection
 */
final class ValueObjectCollectionTest extends TestCase
{
    public function testCreateEmptyCollection(): void
    {
        $emptyCollection = new UserIdCollection([]);

        $this->assertEmpty($emptyCollection->toArray());
    }

    public function testCreateUserIdCollectionExpectsSuccess(): void
    {
        $ids = [1, 5, 17, 997, 2023];

        $userIdCollection = new UserIdCollection($ids);
        $collection = $userIdCollection->getCollection();

        $this->assertSame($ids, $userIdCollection->toArray());
        $this->assertInstanceOf(UserId::class, $collection[array_rand($collection)]);
    }

    public function testCreateUserRoleCollectionExpectsSuccess(): void
    {
        $roles = [1, 2, 4];

        $userRoleCollection = new UserRoleCollection($roles);
        $collection = $userRoleCollection->getCollection();

        $this->assertSame($roles, $userRoleCollection->toArray());
        $this->assertInstanceOf(UserRole::class, $collection[array_rand($collection)]);
    }

    public function testCreateUserEmailDomainNameCollection(): void
    {
        $domainNames = ['wp.pl', 'gmail.com'];

        $userEmailDomainNameCollection = new UserEmailDomainNameCollection($domainNames);
        $collection = $userEmailDomainNameCollection->getCollection();

        $this->assertSame($domainNames, $userEmailDomainNameCollection->toArray());
        $this->assertInstanceOf(UserEmailDomainName::class, $collection[array_rand($collection)]);
    }
}
