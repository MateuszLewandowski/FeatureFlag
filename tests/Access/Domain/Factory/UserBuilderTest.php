<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\Factory;

use FeatureFlag\Access\Domain\Factory\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @covers UserBuilder
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmail
 * @covers User
 */
final class UserBuilderTest extends TestCase
{
    public static function scenarios(): array
    {
        return [
            [1, 2, 'user@email.com'],
            [null, 4, 'john@wp.pl'],
            [null, null, 'email@domain.com'],
            [null, null, null],
            [1, null, null],
            [null, 6, null],
            [445, null, 'email@wp.pl'],
        ];
    }

    /**
     * @dataProvider scenarios
     */
    public function testBuildUser(?int $id, ?int $role, ?string $email): void
    {
        $user = UserBuilder::create()->setId($id)->setRole($role)->setEmail($email)->build();

        $this->assertSame($id, $user->id?->value);
        $this->assertSame($role, $user->role?->value);
        $this->assertSame($email, $user->email?->value);
    }

    public function testBuildEmptyUser(): void
    {
        $user = UserBuilder::create()->build();

        $this->assertNull($user->id);
        $this->assertNull($user->role);
        $this->assertNull($user->email);
    }
}
