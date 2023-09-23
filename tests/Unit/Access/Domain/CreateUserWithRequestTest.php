<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain;

use FeatureFlag\Access\Application\Factory\UserFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \FeatureFlag\Access\Domain\Entity\User
 * @covers \FeatureFlag\Access\Application\Builder\UserBuilder
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserRole
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmail
 * @covers \FeatureFlag\Access\Application\Factory\UserFactory
 */
final class CreateUserWithRequestTest extends TestCase
{
    public function testCreateUser(): void
    {
        $request = new Request([
            'userId' => 1,
            'userRole' => 2,
            'userEmail' => 'user@gmail.com',
        ]);

        $user = UserFactory::createWithRequest($request);

        $this->assertSame(1, $user->id->value);
        $this->assertSame(2, $user->role->value);
        $this->assertSame('user@gmail.com', $user->email->value);
    }
}
