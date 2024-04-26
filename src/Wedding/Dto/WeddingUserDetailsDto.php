<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\RoleType;

readonly class WeddingUserDetailsDto {

  public function __construct(
      public int $id,
      public string $username,
      public string $email,
      public RoleType $role) {}
}