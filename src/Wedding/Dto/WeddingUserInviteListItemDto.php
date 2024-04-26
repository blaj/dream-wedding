<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\RoleType;
use DateTimeImmutable;

readonly class WeddingUserInviteListItemDto {

  public function __construct(
      public int $id,
      public string $userEmail,
      public DateTimeImmutable $createdAt,
      public RoleType $role,
      public bool $canResend) {}
}