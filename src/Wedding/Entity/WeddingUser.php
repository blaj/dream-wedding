<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\User\Entity\User;
use App\Wedding\Entity\Enum\RoleType;
use App\Wedding\Repository\WeddingUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: WeddingUserRepository::class)]
#[Table(name: 'wedding_user', schema: 'wedding')]
class WeddingUser extends AuditingEntity {

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'weddingUsers')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  #[ManyToOne(targetEntity: User::class, fetch: 'LAZY', inversedBy: 'weddingUsers')]
  #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private User $user;

  #[Column(name: 'type', type: Types::STRING, length: 20, nullable: false, enumType: RoleType::class)]
  private RoleType $role;

  public function getWedding(): Wedding {
    return $this->wedding;
  }

  public function setWedding(Wedding $wedding): self {
    $this->wedding = $wedding;

    return $this;
  }

  public function getUser(): User {
    return $this->user;
  }

  public function setUser(User $user): self {
    $this->user = $user;

    return $this;
  }

  public function getRole(): RoleType {
    return $this->role;
  }

  public function setRole(RoleType $role): self {
    $this->role = $role;

    return $this;
  }
}