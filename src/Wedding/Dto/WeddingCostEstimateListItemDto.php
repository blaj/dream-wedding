<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\UnitType;
use Money\Money;

readonly class WeddingCostEstimateListItemDto {

  public function __construct(
      public int $id,
      public string $name,
      public Money $estimate,
      public Money $real,
      public int $quantity,
      public UnitType $unitType,
      public bool $dependsOnGuests) {}
}