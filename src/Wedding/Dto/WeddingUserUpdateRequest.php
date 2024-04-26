<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\RoleType;

class WeddingUserUpdateRequest {

  private string $username;

  private string $userEmail;

  private RoleType $role;

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getUserEmail(): string {
    return $this->userEmail;
  }

  public function setUserEmail(string $userEmail): self {
    $this->userEmail = $userEmail;

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