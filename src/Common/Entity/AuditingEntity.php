<?php

namespace App\Common\Entity;

use App\User\Entity\User;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\PreUpdate;

#[MappedSuperclass]
#[HasLifecycleCallbacks]
class AuditingEntity extends IdEntity implements SoftDeleteInterface {

  #[Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE, nullable: false)]
  protected DateTimeImmutable $createdAt;

  #[ManyToOne(targetEntity: User::class, fetch: 'LAZY')]
  #[JoinColumn(name: 'created_by_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  protected ?User $createdBy = null;

  #[Column(name: 'updated_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
  protected ?DateTimeImmutable $updatedAt = null;

  #[ManyToOne(targetEntity: User::class, fetch: 'LAZY')]
  #[JoinColumn(name: 'updated_by_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  protected ?User $updatedBy = null;

  #[Column(name: 'deleted_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
  protected ?DateTimeImmutable $deletedAt = null;

  #[ManyToOne(targetEntity: User::class, fetch: 'LAZY')]
  #[JoinColumn(name: 'deleted_by_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  protected ?User $deletedBy = null;

  #[Column(name: 'deleted', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  protected bool $deleted = false;

  public function getCreatedAt(): DateTimeImmutable {
    return $this->createdAt;
  }

  public function setCreatedAt(DateTimeImmutable $createdAt): self {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getCreatedBy(): ?User {
    return $this->createdBy;
  }

  public function setCreatedBy(?User $createdBy): self {
    $this->createdBy = $createdBy;

    return $this;
  }

  public function getUpdatedAt(): ?DateTimeImmutable {
    return $this->updatedAt;
  }

  public function setUpdatedAt(?DateTimeImmutable $updatedAt): self {
    $this->updatedAt = $updatedAt;

    return $this;
  }

  public function getUpdatedBy(): ?User {
    return $this->updatedBy;
  }

  public function setUpdatedBy(?User $updatedBy): self {
    $this->updatedBy = $updatedBy;

    return $this;
  }

  public function getDeletedAt(): ?DateTimeImmutable {
    return $this->deletedAt;
  }

  public function setDeletedAt(?DateTimeImmutable $deletedAt): self {
    $this->deletedAt = $deletedAt;

    return $this;
  }

  public function getDeletedBy(): ?User {
    return $this->deletedBy;
  }

  public function setDeletedBy(?User $deletedBy): self {
    $this->deletedBy = $deletedBy;

    return $this;
  }

  public function isDeleted(): bool {
    return $this->deleted;
  }

  public function setDeleted(bool $deleted): self {
    $this->deleted = $deleted;

    return $this;
  }
}