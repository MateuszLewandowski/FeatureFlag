<?php

declare(strict_types=1);

namespace App\Tests\Access\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeZone;
use FeatureFlag\Access\Domain\ValueObject\DateThreshold;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Domain\ValueObject\DateThreshold
 */
class DateThresholdTest extends TestCase
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
    public function testCreateValidDateThreshold(DateTimeImmutable $dateTime): void
    {
        $dateThreshold = new DateThreshold($dateTime);

        $this->assertSame($dateTime->format('Y-m-d'), $dateThreshold->value->format('Y-m-d'));
    }

    public function testSerializeDateThreshold(): void
    {
        $date = '2023-01-01';
        $timeZone = new DateTimeZone('Europe/Vatican');
        $dateThreshold = new DateThreshold(new DateTimeImmutable($date, $timeZone));

        $serializedDateThreshold = $dateThreshold->jsonSerialize();

        $this->assertSame($date, $serializedDateThreshold['date']);
        $this->assertSame($timeZone->getName(), $serializedDateThreshold['timeZone']);
    }
}
