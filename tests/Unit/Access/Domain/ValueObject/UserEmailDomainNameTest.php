<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidUserEmailDomainNameException;
use FeatureFlag\Access\Application\Exception\UserEmailNotFoundException;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Application\Exception\InvalidUserEmailDomainNameException
 * @covers \FeatureFlag\Access\Application\Exception\InvalidUserEmailException
 * @covers \FeatureFlag\Access\Application\Exception\UserRoleNotFoundException
 * @covers \FeatureFlag\Access\Application\Exception\UserEmailNotFoundException
 * @covers \FeatureFlag\Access\Domain\Entity\User
 */
class UserEmailDomainNameTest extends TestCase
{
    public static function valid(): array
    {
        return [
            ['gmail.com'],
            ['o2.pl'],
        ];
    }

    public static function invalid(): array
    {
        return [
            ['!@#$%^'],
            ['gmail'],
            ['domain..pl'],
            ['a;'],
            ['1ł'],
            ['$'],
            ['/??'],
            ['a'],
        ];
    }

    /** @dataProvider valid */
    public function testCreateValidEmailDomainName(string $validEmailDomainName): void
    {
        $userEmailDomainName = new UserEmailDomainName($validEmailDomainName);

        $this->assertSame($validEmailDomainName, $userEmailDomainName->value);
    }

    /** @dataProvider invalid */
    public function testHandleExceptionDuringCreatingEmailDomainName(string $invalidEmailDomainName): void
    {
        $this->expectException(InvalidUserEmailDomainNameException::class);

        new UserEmailDomainName($invalidEmailDomainName);
    }

    public function testCreateWithUserWithNoEmailProvided(): void
    {
        $this->expectException(UserEmailNotFoundException::class);

        UserEmailDomainName::createWithUser(new User(null, null, null));
    }
}
