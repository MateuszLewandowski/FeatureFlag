<?php

declare(strict_types=1);

namespace App\Tests\Access\Application;

use DateTimeImmutable;
use FeatureFlag\Access\Application\FeatureFlagRepository;
use FeatureFlag\Access\Application\VerifyAccessRules;
use FeatureFlag\Access\Domain\Factory\FeatureFlagConfigBuilder;
use FeatureFlag\Access\Domain\Factory\UserBuilder;
use FeatureFlag\Access\Domain\FeatureFlag;
use FeatureFlag\Access\Domain\User;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Persistence\FeatureFlagJsonFileRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FeatureFlag\Access\Application\VerifyAccessRules
 * @covers \FeatureFlag\Access\Domain\Exception\ExpressionNotFoundException
 */
final class VerifyAccessRulesNegativeScenariosTest extends TestCase
{
    private const PATH = __DIR__ . '/../../../src/test.feature-flags.json';

    private FeatureFlagRepository $repository;
    private VerifyAccessRules $verifyAccessRules;

    public static function scenarios(): array
    {
        return [
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_1'),
                    FeatureFlagConfigBuilder::create()
                        ->setDateThreshold([
                            'date' => (new DateTimeImmutable('tomorrow'))->format('Y-m-d'),
                            'timeZone' => 'Europe/Warsaw',
                        ])
                        ->build()
                ),
                UserBuilder::create()->build(),
            ],
            [
                new FeatureFlag(
                    new FeatureFlagId('Key_2'),
                    FeatureFlagConfigBuilder::create()
                        ->setDateThreshold([
                            'date' => (new DateTimeImmutable('yesterday'))->format('Y-m-d'),
                            'timeZone' => 'Europe/Warsaw',
                        ])
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
        $this->repository = new FeatureFlagJsonFileRepository(self::PATH);
        $this->verifyAccessRules = new VerifyAccessRules($this->repository);

        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @dataProvider scenarios */
    public function testVerify(FeatureFlag $featureFlag, User $user): void
    {
        $this->repository->set($featureFlag);

        $this->assertFalse(
            $this->verifyAccessRules->verify($featureFlag->id, $user)
        );

        $this->repository->clean();
    }
}
