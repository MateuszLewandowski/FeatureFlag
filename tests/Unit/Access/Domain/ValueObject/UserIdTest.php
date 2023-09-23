<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidUserIdException;
use FeatureFlag\Access\Domain\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserId
 * @covers \FeatureFlag\Access\Application\Exception\InvalidUserIdException
 */
class UserIdTest extends TestCase
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
            [99],
            [91919],
        ];
    }

    public static function invalid(): array
    {
        return [
            [-1],
            [0],
            [-999],
        ];
    }

    /** @dataProvider valid */
    public function testCreateValidUserId(int $id): void
    {
        $userId = new UserId($id);

        $this->assertSame($id, $userId->value);
    }

    /** @dataProvider invalid */
    public function testHandleExceptionDuringCreatingUserId(int $id): void
    {
        $this->expectException(InvalidUserIdException::class);

        new UserId($id);
    }
}
