<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application;

use FeatureFlag\Access\Application\Factory\AccessSpecificationFactory;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\Exception\ExpressionNotFoundException;
use FeatureFlag\Access\Domain\Specification\Predicates\EnvironmentExtendedExpressible;
use FeatureFlag\Access\Domain\Specification\Predicates\Expressible;
use FeatureFlag\Access\Domain\Specification\Predicates\UserExtendedExpressible;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

final class VerifyAccessRules
{
    public function __construct(
        private readonly FeatureFlagRepository $repository
    ) {}

    public function verify(FeatureFlagId $featureFlagName, User $user): bool
    {
        $featureFlag = $this->repository->get($featureFlagName);

        if ($featureFlag->config->forceGrantAccess) {
            return true;
        }

        $accessSpecificationSet = AccessSpecificationFactory::create($featureFlag)->pull();

        return array_reduce($accessSpecificationSet, static fn(bool $isExpressionSatisfied, Expressible $expressible) => match (true) {
                $expressible instanceof EnvironmentExtendedExpressible => $expressible->execute($featureFlag),
                $expressible instanceof UserExtendedExpressible => $expressible->execute($featureFlag, $user),
                default => throw new ExpressionNotFoundException($expressible),
            } && $isExpressionSatisfied, true);
    }
}
