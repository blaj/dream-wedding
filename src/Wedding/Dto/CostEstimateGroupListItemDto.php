<?php

namespace App\Wedding\Dto;

readonly class CostEstimateGroupListItemDto {

  public function __construct(public int $id, public string $name) {}
}