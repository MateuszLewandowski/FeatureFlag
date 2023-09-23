<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidUserRoleException;
use FeatureFlag\Access\Domain\ValueObject\UserRole;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Application\Exception\InvalidUserRoleException
 */
class UserRoleTest extends TestCase
{
    public static function valid(): array
    {
        return [
            [1],
            [2],
            [4],
            [3],
            [5],
            [10],
            [18],
        ];
    }

    public static function invalid(): array
    {
        return [
            [-1],
            [0],
            [19],
        ];
    }

    /** @dataProvider valid */
    public function testCreateValidUserRole(int $role): void
    {
        $userRole = new UserRole($role);

        $this->assertSame($role, $userRole->value);
    }

    /** @dataProvider invalid */
    public function testHandleExceptionDuringCreatingUserRole(int $role): void
    {
        $this->expectException(InvalidUserRoleException::class);

        new UserRole($role);
    }
}
