<?php

namespace App\User\Dto;

use App\User\Validator\MatchCurrentPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

#[MatchCurrentPassword]
class UserSettingsRequest {

  private int $userId;

  #[NotBlank]
  private string $currentPassword;

  #[NotBlank]
  #[Length(min: 8, max: 50)]
  #[NotCompromisedPassword]
  private string $newPassword;

  public function getUserId(): int {
    return $this->userId;
  }

  public function setUserId(int $userId): self {
    $this->userId = $userId;

    return $this;
  }

  public function getCurrentPassword(): string {
    return $this->currentPassword;
  }

  public function setCurrentPassword(string $currentPassword): self {
    $this->currentPassword = $currentPassword;

    return $this;
  }

  public function getNewPassword(): string {
    return $this->newPassword;
  }

  public function setNewPassword(string $newPassword): self {
    $this->newPassword = $newPassword;

    return $this;
  }
}