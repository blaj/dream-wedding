<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingCostEstimateDetailsDto;
use App\Wedding\Entity\WeddingCostEstimate;

class WeddingCostEstimateDetailsDtoMapper {

  public static function map(
      ?WeddingCostEstimate $weddingCostEstimate): ?WeddingCostEstimateDetailsDto {
    if ($weddingCostEstimate === null) {
      return null;
    }

    return new WeddingCostEstimateDetailsDto(
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