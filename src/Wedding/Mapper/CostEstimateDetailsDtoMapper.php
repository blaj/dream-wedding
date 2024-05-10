<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateDetailsDto;
use App\Wedding\Entity\CostEstimate;

class CostEstimateDetailsDtoMapper {

  public static function map(
      ?CostEstimate $weddingCostEstimate): ?CostEstimateDetailsDto {
    if ($weddingCostEstimate === null) {
      return null;
    }

    return new CostEstimateDetailsDto(
        $weddingCostEstimate->getId(),
        $weddingCostEstimate->getName(),
        $weddingCostEstimate->getDescription(),
        $weddingCostEstimate->getEstimate(),
        $weddingCostEstimate->getReal(),
        $weddingCostEstimate->getQuantity(),
        $weddingCostEstimate->getUnitType(),
        $weddingCostEstimate->isDependsOnGuests());
  }
}