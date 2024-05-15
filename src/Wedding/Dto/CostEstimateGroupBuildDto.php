<?php

namespace App\Wedding\Dto;

class CostEstimateGroupBuildDto {


  private CostEstimateGroupListItemDto $costEstimateGroupListItemDto;

  /**
   * @var array<CostEstimateListItemDto>
   */
  private array $costEstimatesListItemDto = [];

  public function getCostEstimateGroupListItemDto(): CostEstimateGroupListItemDto {
    return $this->costEstimateGroupListItemDto;
  }

  public function setCostEstimateGroupListItemDto(
      CostEstimateGroupListItemDto $costEstimateGroupListItemDto): self {
    $this->costEstimateGroupListItemDto = $costEstimateGroupListItemDto;

    return $this;
  }

  /**
   * @return array<CostEstimateListItemDto>
   */
  public function getCostEstimatesListItemDto(): array {
    return $this->costEstimatesListItemDto;
  }

  /**
   * @param array<CostEstimateListItemDto> $costEstimatesListItemDto
   */
  public function setCostEstimatesListItemDto(
      array $costEstimatesListItemDto): self {
    $this->costEstimatesListItemDto = $costEstimatesListItemDto;

    return $this;
  }

  public function addCostEstimateListItemDto(CostEstimateListItemDto $costEstimateListItemDto): self {
    $this->costEstimatesListItemDto[] = $costEstimateListItemDto;

    return $this;
  }
}