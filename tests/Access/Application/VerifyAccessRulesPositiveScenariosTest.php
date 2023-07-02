<?php

declare(strict_types=1);

namespace App\Tests\Access\Application;

use DateTimeImmutable;
use FeatureFlag\Access\Application\FeatureFlagRepository;
use FeatureFlag\Access\Application\VerifyAccessRules;
use FeatureFlag\Access\Domain\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\Builder\UserBuilder;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Persistence\JsonFileRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\VerifyAccessRules
 * @covers \FeatureFlag\Access\Infrastructure\Persistence\JsonFileRepository
 * @covers \FeatureFlag\Access\Domain\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Domain\Factory\AccessSpecificationFactory
 * @covers \FeatureFlag\Access\Domain\Specification\AccessSpecification
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsStartsAtDateExceeded
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsEndsAtDateExceeded
 * @covers \FeatureFlag\Access\Infrastructure\Persistence\JsonFileRepository
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 * @covers \FeatureFlag\Access\Domain\ValueObject\StartsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\EndsAt
 */
final class VerifyAccessRulesPositiveScenariosTest extends TestCase
{
    private const PATH = __DIR__ . '/../../../src/test.feature-flags.json';

    private FeatureFlagRepository $repository;
    private VerifyAccessRules $verifyAccessRules;

    public static function scenarios(): array
    {
        return [
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_4'),
                    FeatureFlagConfigBuilder::create()
                        ->setStartsAt((new DateTimeImmutable('previous monday'))->format('Y-m-d H:i:s'))
                        ->setEndsAt((new DateTimeImmutable('next friday'))->format('Y-m-d H:i:s'))
                        ->build()
                ),
                UserBuilder::create()->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_5'),
                    FeatureFlagConfigBuilder::create()
                        ->setStartsAt((new DateTimeImmutable('midnight'))->format('Y-m-d H:i:s'))
                        ->setUserRoles([1, 2, 3])
                        ->setUserIds([1, 2, 3])
                        ->build()
                ),
                UserBuilder::create()->setId(2)->setRole(3)->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_6'),
                    FeatureFlagConfigBuilder::create()
                        ->setForceGrantAccess(true)
                        ->build()
                ),
                UserBuilder::create()->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_7'),
                    FeatureFlagConfigBuilder::create()
                        ->setForceGrantAccess(true)
                        ->setUserIds([1, 2, 3])
                        ->build()
                ),
                UserBuilder::create()->setId(5)->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_8'),
                    FeatureFlagConfigBuilder::create()
                        ->setForceGrantAccess(false)
                        ->setUserIds([1, 2, 3])
                        ->setUserRoles([4, 5, 6])
                        ->setUserEmailDomainNames(['wp.pl', 'gmail.com'])
                        ->setModuloUserId(3)
                        ->setStartsAt((new DateTimeImmutable('yesterday'))->format('Y-m-d H:i:s'))
                        ->setEndsAt((new DateTimeImmutable('next friday'))->format('Y-m-d H:i:s'))
                        ->build()
                ),
                UserBuilder::create()->setId(3)->setRole(6)->setEmail('user@gmail.com')->build(),
            ],
        ];
    }

    public function setUp(): void
    {
        $this->repository = new JsonFileRepository(self::PATH);
        $this->verifyAccessRules = new VerifyAccessRules($this->repository);

        parent::setUp();
    }

    /** @dataProvider scenarios */
    public function testVerify(FeatureFlag $featureFlag, User $user): void
    {
        $this->repository->set($featureFlag);

        $this->assertTrue(
            $this->verifyAccessRules->verify($featureFlag->id, $user)
        );

        $this->repository->clean();
    }
}
