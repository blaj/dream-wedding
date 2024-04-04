<?php

namespace App\User\Entity;

use App\Common\Entity\AuditingEntity;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'users', schema: 'users')]
class User extends AuditingEntity implements PasswordAuthenticatedUserInterface {

  #[Column(name: 'username', type: Types::STRING, length: 50, nullable: false)]
  private string $username;

  #[Column(name: 'password', type: Types::STRING, length: 200, nullable: false)]
  private string $password;

  #[Column(name: 'email', type: Types::STRING, length: 200, nullable: false)]
  private string $email;

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }
}