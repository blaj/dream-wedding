<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\DietType;
use App\Wedding\Entity\Enum\GuestType;

readonly class GuestDetailsDto {

  /**
   * @param array<string> $groupNames
   */
  public function __construct(
      public int $id,
      public string $firstName,
      public string $lastName,
      public GuestType $type,
      public bool $invited,
      public bool $confirmed,
      public bool $accommodation,
      public bool $transport,
      public DietType $dietType,
      public ?string $note,
      public ?string $telephone,
      public ?string $email,
      public int $payment,
      public array $groupNames) {}
}