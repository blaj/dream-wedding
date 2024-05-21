<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\UnitType;
use Money\Money;

readonly class CostEstimateListItemDto {

  public function __construct(
      public int $id,
      public string $name,
      public Money $cost,
      public Money $advancePayment,
      public int $quantity,
      public UnitType $unitType,
      public bool $dependsOnGuests,
      public Money $paid) {}
}