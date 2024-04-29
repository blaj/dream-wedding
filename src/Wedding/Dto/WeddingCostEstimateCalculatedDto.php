<?php

namespace App\Wedding\Dto;

use Money\Money;

readonly class WeddingCostEstimateCalculatedDto {

  public function __construct(
      public Money $estimateCost,
      public Money $realCost,
      public Money $toPay) {}
}