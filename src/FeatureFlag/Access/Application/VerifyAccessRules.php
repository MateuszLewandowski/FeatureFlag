<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application;

use FeatureFlag\Access\Application\Exception\ExpressionNotFoundException;
use FeatureFlag\Access\Application\Factory\AccessSpecificationFactory;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Specification\Predicates\EnvironmentExtendedExpressible;
use FeatureFlag\Access\Domain\Specification\Predicates\Expressible;
use FeatureFlag\Access\Domain\Specification\Predicates\UserExtendedExpressible;

final class VerifyAccessRules
{
    public function verify(FeatureFlag $featureFlag, User $user): bool
    {
        if ($featureFlag->config->forceGrantAccess) {
            return true;
        }

        $accessSpecificationSet = AccessSpecificationFactory::create($featureFlag)->pull();

        return array_reduce($accessSpecificationSet, static fn (bool $isExpressionSatisfied, Expressible $expressible) => match (true) {
                $expressible instanceof EnvironmentExtendedExpressible => $expressible->execute($featureFlag),
                $expressible instanceof UserExtendedExpressible => $expressible->execute($featureFlag, $user),
                default => throw new ExpressionNotFoundException($expressible),
            } && $isExpressionSatisfied, true);
    }
}
