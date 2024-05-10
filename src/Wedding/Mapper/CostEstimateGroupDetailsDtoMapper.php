<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateGroupDetailsDto;
use App\Wedding\Entity\CostEstimateGroup;
use App\Wedding\Entity\CostEstimate;

class CostEstimateGroupDetailsDtoMapper {

  public static function map(?CostEstimateGroup $costEstimateGroup): ?CostEstimateGroupDetailsDto {
    if ($costEstimateGroup === null) {
      return null;
    }

    return new CostEstimateGroupDetailsDto(
        $costEstimateGroup->getId(),
        $costEstimateGroup->getName(),
        $costEstimateGroup->getDescription(),
        self::costEstimateNames($costEstimateGroup->getCostEstimates()->toArray()));
  }

  /**
   * @param array<CostEstimate> $costEstimates
   *
   * @return array<string>
   */
  private static function costEstimateNames(array $costEstimates): array {
    return array_map(
        fn (CostEstimate $weddingCostEstimate) => $weddingCostEstimate->getName(),
        $costEstimates);
  }
}