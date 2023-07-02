<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\ValueObject;

use DateTimeImmutable;
use FeatureFlag\Access\Domain\ValueObject\EndsAt;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\EndsAt
 */
class EndsAtTest extends TestCase
{
    public static function valid(): array
    {
        return [
            [new DateTimeImmutable('midnight')],
            [new DateTimeImmutable('tomorrow')],
            [new DateTimeImmutable('next friday')],
            [new DateTimeImmutable('today')],
        ];
    }

    /** @dataProvider valid */
    public function testCreateValidEndsAt(DateTimeImmutable $dateTime): void
    {
        $startsAt = new EndsAt($dateTime);

        $this->assertSame($dateTime->format('Y-m-d H:i:s'), $startsAt->value->format('Y-m-d H:i:s'));
    }

    public function testSerializeStartsAt(): void
    {
        $date = '2023-01-01 10:00:00';
        $startsAt = new EndsAt(new DateTimeImmutable($date));

        $this->assertSame($date, $startsAt->jsonSerialize());
    }
}
