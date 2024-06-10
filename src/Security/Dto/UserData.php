<?php

namespace App\Security\Dto;

use App\User\Entity\Enum\Role;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserData implements UserInterface, PasswordAuthenticatedUserInterface {

  private int $userId;

  private string $userIdentifier;

  private string $password;

  private string $username;

  private string $email;

  private Role $role;

  public function eraseCredentials(): void {}

  /**
   * @return array<string>
   */
  public function getRoles(): array {
    $roles = [$this->role];
    $roles[] = Role::USER;

    return array_unique(array_map(fn (Role $role) => sprintf('ROLE_%s', $role->value), $roles));
  }

  public function getUserId(): int {
    return $this->userId;
  }

  public function setUserId(int $userId): self {
    $this->userId = $userId;

    return $this;
  }

  public function getUserIdentifier(): string {
    return $this->userIdentifier;
  }

  public function setUserIdentifier(string $userIdentifier): self {
    $this->userIdentifier = $userIdentifier;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getRole(): Role {
    return $this->role;
  }

  public function setRole(Role $role): self {
    $this->role = $role;

    return $this;
  }
}