<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateGroupUpdateRequest;
use App\Wedding\Entity\CostEstimateGroup;
use App\Wedding\Entity\WeddingCostEstimate;

class CostEstimateGroupUpdateRequestMapper {

  public static function map(
      ?CostEstimateGroup $costEstimateGroup): ?CostEstimateGroupUpdateRequest {
    if ($costEstimateGroup === null) {
      return null;
    }

    return (new CostEstimateGroupUpdateRequest())
        ->setName($costEstimateGroup->getName())
        ->setDescription($costEstimateGroup->getDescription())
        ->setCostEstimates(self::costEstimates($costEstimateGroup->getCostEstimates()->toArray()));
  }

  /**
   * @param array<WeddingCostEstimate> $costEstimates
   *
   * @return array<int>
   */
  private static function costEstimates(array $costEstimates): array {
    return array_map(
        fn (WeddingCostEstimate $weddingCostEstimate) => $weddingCostEstimate->getId(),
        $costEstimates);
  }
}