<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\TableType;

readonly class TableListItemDto {

  public function __construct(
      public int $id,
      public string $name,
      public TableType $type,
      public int $numberOfSeats,
      public int $numberOfGuests) {}
}