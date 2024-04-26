<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\RoleType;
use App\Wedding\Validator\WeddingUserNotExistsEmail;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class WeddingUserInviteRequest {

  #[NotBlank]
  #[Length(min: 3, max: 200)]
  #[Email]
  #[WeddingUserNotExistsEmail]
  private string $email;

  private RoleType $role;

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

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