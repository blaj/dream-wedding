<?php

namespace App\Security\Dto;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserData implements UserInterface, PasswordAuthenticatedUserInterface {

  private int $userId;

  private string $userIdentifier;

  private string $password;

  private string $username;

  private string $email;

  public function eraseCredentials(): void {}

  /**
   * @return array<string>
   */
  public function getRoles(): array {
    return ['ROLE_USER'];
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
}