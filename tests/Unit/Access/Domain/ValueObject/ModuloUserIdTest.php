<?php

declare(strict_types=1);

namespace App\Tests\Unit\Access\Domain\ValueObject;

use FeatureFlag\Access\Application\Exception\InvalidModuloUserIdException;
use FeatureFlag\Access\Domain\ValueObject\ModuloUserId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\ModuloUserId
 * @covers \FeatureFlag\Access\Application\Exception\InvalidModuloUserIdException
 */
class ModuloUserIdTest extends TestCase
{
    public static function valid(): array
    {
        return [
            [2],
            [4],
            [3],
            [5],
            [10],
            [100],
        ];
    }

    public static function invalid(): array
    {
        return [
            [-5],
            [0],
            [1],
            [101],
        ];
    }

    /** @dataProvider valid */
    public function testCreateValidModuloUserId(int $modulo): void
    {
        $moduloUserId = new ModuloUserId($modulo);

        $this->assertSame($modulo, $moduloUserId->value);
    }

    /** @dataProvider invalid */
    public function testHandleExceptionDuringCreatingModuloUserId(int $modulo): void
    {
        $this->expectException(InvalidModuloUserIdException::class);

        new ModuloUserId($modulo);
    }
}
