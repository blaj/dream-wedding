<?php

namespace App\Wedding\Dto;

readonly class CostEstimateGroupDetailsDto {

  /**
   * @param array<string> $costEstimateNames
   */
  public function __construct(
      public int $id,
      public string $name,
      public ?string $description,
      public array $costEstimateNames) {}
}