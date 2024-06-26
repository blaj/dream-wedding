<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\UnitType;
use Money\Money;

readonly class CostEstimateDetailsDto {

  public function __construct(
      public int $id,
      public string $name,
      public ?string $description,
      public Money $cost,
      public Money $advancePayment,
      public int $quantity,
      public UnitType $unitType,
      public bool $dependsOnGuests,
      public ?string $groupName,
      public int $orderNo,
      public Money $paid) {}
}