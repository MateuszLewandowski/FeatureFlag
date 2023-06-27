<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\ValueObject;

use FeatureFlag\Access\Domain\Exception\InvalidUserEmailException;
use FeatureFlag\Access\Domain\ValueObject\UserEmail;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmail
 * @covers \FeatureFlag\Access\Domain\Exception\InvalidUserEmailException
 */
class UserEmailTest extends TestCase
{
    public static function valid(): array
    {
        return [
            ['user@gmail.com'],
            ['user@o2.pl'],
        ];
    }

    public static function invalid(): array
    {
        return [
            ['!@#$%^'],
            ['gmail'],
            ['domain..pl'],
            ['gmail.pl'],
            ['@o2.pl'],
            ['a;'],
            ['1ł'],
            ['$'],
            ['/??'],
            ['a'],
        ];
    }

    /** @dataProvider valid */
    public function testCreateValidUserEmail(string $email): void
    {
        $userEmail = new UserEmail($email);

        $this->assertSame($email, $userEmail->value);
    }

    /** @dataProvider invalid */
    public function testHandleExceptionDuringCreatingUserEmail(string $email): void
    {
        $this->expectException(InvalidUserEmailException::class);

        new UserEmail($email);
    }
}
