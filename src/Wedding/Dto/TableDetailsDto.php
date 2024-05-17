<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\TableType;

readonly class TableDetailsDto {

  /**
   * @param array<string> $guestNames
   */
  public function __construct(
      public int $id,
      public string $name,
      public TableType $type,
      public ?string $description,
      public int $numberOfSeats,
      public int $numberOfGuests,
      public array $guestNames) {}
}