<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application\Specification\Predicates;

use DateTimeImmutable;
use FeatureFlag\Access\Domain\FeatureFlag;

final class IsDateThresholdExceeded implements EnvironmentExtendedExpressible
{
    private const FORMAT = 'Y-m-d';

    public function execute(FeatureFlag $featureFlag): bool
    {
        $dateThreshold = $featureFlag->config->dateThreshold;
        $now = new DateTimeImmutable('now', $dateThreshold->value->getTimezone());

        return $dateThreshold->value->format(self::FORMAT) <= $now->format(self::FORMAT);
    }
}
