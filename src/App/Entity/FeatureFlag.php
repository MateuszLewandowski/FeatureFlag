<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class FeatureFlag
{
    #[ORM\Id]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $id = null;

    #[ORM\Column]
    private ?bool $force_grant_access = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $starts_at = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $ends_at = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $user_email_domain_names = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $user_ids = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $user_roles = [];

    #[ORM\Column(nullable: true)]
    private ?int $modulo_user_id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updated_at = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $name): static
    {
        $this->id = $name;

        return $this;
    }

    public function isForceGrantAccess(): ?bool
    {
        return $this->force_grant_access;
    }

    public function setForceGrantAccess(bool $force_grant_access): static
    {
        $this->force_grant_access = $force_grant_access;

        return $this;
    }

    public function getStartsAt(): ?DateTimeImmutable
    {
        return $this->starts_at;
    }

    public function setStartsAt(?DateTimeImmutable $starts_at): static
    {
        $this->starts_at = $starts_at;

        return $this;
    }

    public function getEndsAt(): ?DateTimeImmutable
    {
        return $this->ends_at;
    }

    public function setEndsAt(?DateTimeImmutable $ends_at): static
    {
        $this->ends_at = $ends_at;

        return $this;
    }

    public function getUserEmailDomainNames(): array
    {
        return $this->user_email_domain_names;
    }

    public function setUserEmailDomainNames(?array $user_email_domain_names): static
    {
        $this->user_email_domain_names = $user_email_domain_names;

        return $this;
    }

    public function getUserIds(): array
    {
        return $this->user_ids;
    }

    public function setUserIds(?array $user_ids): static
    {
        $this->user_ids = $user_ids;

        return $this;
    }

    public function getModuloUserId(): ?int
    {
        return $this->modulo_user_id;
    }

    public function setModuloUserId(?int $modulo_user_id): static
    {
        $this->modulo_user_id = $modulo_user_id;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUserRoles(): array
    {
        return $this->user_roles;
    }

    public function setUserRoles(?array $user_roles): static
    {
        $this->user_roles = $user_roles;

        return $this;
    }
}
