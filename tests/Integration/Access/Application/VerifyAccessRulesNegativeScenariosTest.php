<?php

declare(strict_types=1);

namespace App\Tests\Integration\Access\Application;

use DateTimeImmutable;
use FeatureFlag\Access\Application\Builder\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Application\Builder\UserBuilder;
use FeatureFlag\Access\Application\ReadableRepository;
use FeatureFlag\Access\Application\VerifyAccessRules;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\Entity\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Persistence\JsonFileRepository;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @covers \FeatureFlag\Access\Application\VerifyAccessRules
 * @covers \FeatureFlag\Access\Infrastructure\Persistence\JsonFileRepository
 * @covers \FeatureFlag\Access\Application\Collection\ValueObjectCollection
 * @covers \FeatureFlag\Access\Application\Factory\AccessSpecificationFactory
 * @covers \FeatureFlag\Access\Domain\Specification\AccessSpecification
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsStartsAtDateExceeded
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsEndsAtDateExceeded
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserIdAvailable
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\IsUserRoleAvailable
 * @covers \FeatureFlag\Access\Infrastructure\Persistence\JsonFileRepository
 * @covers \FeatureFlag\Access\Domain\ValueObject\UserEmailDomainName
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserIdSatisfyModulo
 * @covers \FeatureFlag\Access\Domain\Specification\Predicates\DoesUserEmailAddressIncludesDomain
 * @covers \FeatureFlag\Access\Domain\ValueObject\StartsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\EndsAt
 * @covers \FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig
 */
final class VerifyAccessRulesNegativeScenariosTest extends TestCase
{
    private const PATH = __DIR__ . '/../../../src/test.feature-flags.json';

    private ReadableRepository $repository;
    private VerifyAccessRules $verifyAccessRules;

    public static function scenarios(): array
    {
        return [
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_1'),
                    FeatureFlagConfigBuilder::create()
                        ->setStartsAt((new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'))
                        ->setEndsAt((new DateTimeImmutable('next monday'))->format('Y-m-d H:i:s'))
                        ->build()
                ),
                UserBuilder::create()->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_1'),
                    FeatureFlagConfigBuilder::create()
                        ->setStartsAt((new DateTimeImmutable('today -5 days'))->format('Y-m-d H:i:s'))
                        ->setEndsAt((new DateTimeImmutable('yesterday'))->format('Y-m-d H:i:s'))
                        ->build()
                ),
                UserBuilder::create()->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_2'),
                    FeatureFlagConfigBuilder::create()
                        ->setStartsAt((new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'))
                        ->setUserRoles([1, 2, 3])
                        ->setUserIds([1, 2, 3])
                        ->build()
                ),
                UserBuilder::create()->setId(4)->setRole(1)->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_3'),
                    FeatureFlagConfigBuilder::create()
                        ->setForceGrantAccess(false)
                        ->setUserIds([1, 2, 3])
                        ->setUserRoles([1, 2, 3])
                        ->setUserEmailDomainNames(['gmail.com', 'wp.pl'])
                        ->setModuloUserId(7)
                        ->build()
                ),
                UserBuilder::create()->setId(2)->setRole(3)->setEmail('user@wp.pl')->build(),
            ],
        ];
    }

    public function setUp(): void
    {
        $this->repository = new JsonFileRepository(self::PATH);
        $this->verifyAccessRules = new VerifyAccessRules();

        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @dataProvider scenarios */
    public function testVerify(FeatureFlag $featureFlag, User $user): void
    {
        try {
            $this->repository->set($featureFlag);

            $this->assertFalse(
                $this->verifyAccessRules->verify($featureFlag, $user)
            );

            $this->repository->clean();
        } catch (Throwable $e) {
            dd($e);
        }
    }
}
