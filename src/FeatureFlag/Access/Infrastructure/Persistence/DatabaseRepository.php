<?php

declare(strict_types=1);

namespace FeatureFlag\Access\Infrastructure\Persistence;

use App\Entity\FeatureFlag as Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use FeatureFlag\Access\Application\Factory\FeatureFlagConfigFactory;
use FeatureFlag\Access\Application\ReadableRepository;
use FeatureFlag\Access\Application\SettableRepository;
use FeatureFlag\Access\Domain\Entity\FeatureFlag;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagConfig;
use FeatureFlag\Access\Domain\ValueObject\FeatureFlagId;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagAlreadyExistsException;
use FeatureFlag\Access\Infrastructure\Exception\FeatureFlagNotFoundException;
use FeatureFlag\Access\Infrastructure\Exception\PersistenceRuntimeException;
use Throwable;

final class DatabaseRepository extends ServiceEntityRepository implements ReadableRepository, SettableRepository
{
    private readonly Connection $connection;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entity::class);
        $this->connection = $this->getEntityManager()->getConnection();
    }

    /** @return FeatureFlag[] */
    public function getFeatureFlags(): array
    {
        $featureFlagsEntities = $this->findAll();

        if (empty($featureFlagsEntities)) {
            return [];
        }

        return array_map(static function (Entity $entity) {
            return new FeatureFlag(
                new FeatureFlagId($entity->getId()),
                FeatureFlagConfigFactory::createWithEntity($entity)
            );
        }, $featureFlagsEntities);
    }

    public function get(FeatureFlagId $id): FeatureFlag
    {
        /** @var Entity $featureFlagEntity */
        $featureFlagEntity = $this->find($id->value);

        if (!$featureFlagEntity) {
            throw new FeatureFlagNotFoundException($id);
        }

        return new FeatureFlag(
            new FeatureFlagId($featureFlagEntity->getId()),
            FeatureFlagConfigFactory::createWithEntity($featureFlagEntity)
        );
    }

    public function set(FeatureFlag $featureFlag): self
    {
        $doesFeatureFlagExists = $this->count([
            'id' => $featureFlag->id->value,
        ]);

        if ($doesFeatureFlagExists) {
            throw new FeatureFlagAlreadyExistsException($featureFlag);
        }

        try {
            $stmt = $this->connection->prepare(
                <<<SQL
                    INSERT INTO feature_flag 
                        (id, force_grant_access, starts_at, ends_at, user_email_domain_names, user_ids, user_roles, modulo_user_id, created_at, updated_at) 
                    VALUES 
                        (:id, :force_grant_access, :starts_at, :ends_at, :user_email_domain_names, :user_ids, :user_roles, :modulo_user_id, :created_at, :updated_at)
                SQL
            );

            $timestamp = date('Y-m-d H:i:s');

            $stmt->executeStatement(
                array_merge([
                    'id' => $featureFlag->id->value,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ], $featureFlag->config->databaseSerialize())
            );
        } catch (Throwable $e) {
            throw new PersistenceRuntimeException($e);
        }

        return $this;
    }

    public function delete(FeatureFlagId $id): self
    {
        try {
            $stmt = $this->connection->prepare(
                <<<SQL
                    DELETE FROM feature_flag WHERE id = :id
                SQL
            );

            $stmt->executeStatement([
                'id' => $id->value,
            ]);
        } catch (Exception $e) {
            throw new PersistenceRuntimeException($e);
        }

        return $this;
    }

    public function update(FeatureFlagId $id, FeatureFlagConfig $config): self
    {
        try {
            $stmt = $this->connection->prepare(
                <<<SQL
                UPDATE 
                    feature_flag 
                SET 
                    id = :id,
                    force_grant_access = :force_grant_access,
                    starts_at = :starts_at,
                    ends_at = :ends_at,
                    user_email_domain_names = :user_email_domain_names,
                    user_ids = :user_ids,
                    user_roles = :user_roles,
                    modulo_user_id = :modulo_user_id,
                    updated_at = :updated_at
                WHERE
                    id = :id
                SQL
            );

            $stmt->executeStatement(
                array_merge([
                    'id' => $id->value,
                    'updated_at' => date('Y-m-d H:i:s'),
                ], $config->databaseSerialize())
            );
        } catch (Throwable $e) {
            throw new PersistenceRuntimeException($e);
        }

        return $this;
    }
}
