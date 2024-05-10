<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateListItemDto;
use App\Wedding\Entity\CostEstimate;

class CostEstimateListItemDtoMapper {

  public static function map(
      ?CostEstimate $weddingCostEstimate): ?CostEstimateListItemDto {
    if ($weddingCostEstimate === null) {
      return null;
    }

    return new CostEstimateListItemDto(
        $weddingCostEstimate->getId(),
        $weddingCostEstimate->getName(),
        $weddingCostEstimate->getEstimate(),
        $weddingCostEstimate->getReal(),
        $weddingCostEstimate->getQuantity(),
        $weddingCostEstimate->getUnitType(),
        $weddingCostEstimate->isDependsOnGuests());
  }
}