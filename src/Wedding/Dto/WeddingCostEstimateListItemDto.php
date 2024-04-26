<?php

namespace App\Wedding\Dto;

use Money\Money;

readonly class WeddingCostEstimateListItemDto {

  public function __construct(
      public int $id,
      public string $name,
      public Money $estimate,
      public Money $real) {}
}