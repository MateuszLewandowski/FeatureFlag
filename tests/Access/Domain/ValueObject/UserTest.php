<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\ValueObject;

use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\ValueObject\UserEmail;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\Entity\User
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmail
 * @covers \FeatureFlag\Access\Domain\Exception\InvalidUserIdException
 * @covers \FeatureFlag\Access\Domain\Exception\InvalidUserRoleException
 * @covers \FeatureFlag\Access\Domain\Exception\InvalidUserEmailException
 */
final class UserTest extends TestCase
{
    public static function scenarios(): array
    {
        return [
            [new UserId(20533), new UserRole(7), new UserEmail('user@gmail.com')],
            [null, new UserRole(8), new UserEmail('user1@gmail.com')],
            [new UserId(20534), null, new UserEmail('user2@gmail.com')],
            [new UserId(20535), new UserRole(9), null],
            [null, null, null],
        ];
    }

    /** @dataProvider scenarios */
    public function testCreateUser(?UserId $userId, ?UserRole $userRole, ?UserEmail $userEmail): void
    {
        $user = new User($userId, $userRole, $userEmail);

        $this->assertSame($userId?->value, $user->id?->value);
        $this->assertSame($userRole?->value, $user->role?->value);
        $this->assertSame($userEmail?->value, $user->email?->value);
    }
}
