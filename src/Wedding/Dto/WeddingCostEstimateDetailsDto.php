<?php

namespace App\Wedding\Dto;

use Money\Money;

readonly class WeddingCostEstimateDetailsDto {

  public function __construct(
      public int $id,
      public string $name,
      public ?string $description,
      public Money $estimate,
      public Money $real) {}
}