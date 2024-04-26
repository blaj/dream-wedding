<?php

namespace App\Wedding\Entity;

use App\Common\Entity\AuditingEntity;
use App\Wedding\Entity\Enum\RoleType;
use App\Wedding\Repository\WeddingUserInviteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: WeddingUserInviteRepository::class)]
#[Table(name: 'wedding_user_invite', schema: 'wedding')]
class WeddingUserInvite extends AuditingEntity {

  #[ManyToOne(targetEntity: Wedding::class, fetch: 'LAZY', inversedBy: 'weddingUserInvites')]
  #[JoinColumn(name: 'wedding_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  private Wedding $wedding;

  #[Column(name: 'user_email', type: Types::STRING, length: 200, nullable: false)]
  private string $userEmail;

  #[Column(name: 'token', type: Types::STRING, length: 100, nullable: false)]
  private string $token;

  #[Column(name: 'role', type: Types::STRING, length: 20, nullable: false, enumType: RoleType::class)]
  private RoleType $role;

  public function getWedding(): Wedding {
    return $this->wedding;
  }

  public function setWedding(Wedding $wedding): self {
    $this->wedding = $wedding;

    return $this;
  }

  public function getUserEmail(): string {
    return $this->userEmail;
  }

  public function setUserEmail(string $userEmail): self {
    $this->userEmail = $userEmail;

    return $this;
  }

  public function getToken(): string {
    return $this->token;
  }

  public function setToken(string $token): self {
    $this->token = $token;

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