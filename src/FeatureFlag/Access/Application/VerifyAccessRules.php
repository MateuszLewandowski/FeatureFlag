<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Application;

use FeatureFlag\Access\Application\Exception\ExpressionNotFoundException;
use FeatureFlag\Access\Application\Specification\Predicates\EnvironmentExtendedExpressible;
use FeatureFlag\Access\Application\Specification\Predicates\Expressible;
use FeatureFlag\Access\Application\Specification\Predicates\UserExtendedExpressible;
use FeatureFlag\Access\Domain\Factory\AccessSpecificationFactory;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;

final class VerifyAccessRules
{
    public function __construct(
        private readonly FeatureFlagRepository $repository
    ) {}

    public function verify(FeatureFlagId $id, User $user): bool
    {
        $featureFlag = $this->repository->get($id);

        if ($featureFlag->config->forceGrantAccess) {
            return true;
        }

        $accessSpecificationSet = AccessSpecificationFactory::create($featureFlag)->pull();

        return array_reduce($accessSpecificationSet, static fn(bool $isExpressionSatisfied, Expressible $expressible) => match (true) {
                $expressible instanceof EnvironmentExtendedExpressible => $expressible->execute($featureFlag),
                $expressible instanceof UserExtendedExpressible => $expressible->execute($featureFlag, $user),
                default => throw new ExpressionNotFoundException(),
            } && $isExpressionSatisfied, true);
    }
}
