<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\RoleType;

readonly class WeddingUserListItemDto {

  public function __construct(
      public int $id,
      public string $username,
      public string $email,
      public RoleType $role,
      public bool $canDelete) {}
}