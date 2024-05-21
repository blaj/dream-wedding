<?php

namespace App\Wedding\Dto;

use Money\Money;

readonly class CostEstimateCalculatedDto {

  public function __construct(
      public Money $cost,
      public Money $advancePayment,
      public Money $paid,
      public Money $toPay,
      public int $budgetPercentage) {}
}