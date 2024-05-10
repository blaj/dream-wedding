<?php

namespace App\Wedding\Dto;

use Money\Money;

readonly class CostEstimateCalculatedDto {

  public function __construct(
      public Money $estimateCost,
      public Money $realCost,
      public Money $toPay) {}
}