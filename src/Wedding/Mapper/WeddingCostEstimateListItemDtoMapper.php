<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingCostEstimateListItemDto;
use App\Wedding\Entity\WeddingCostEstimate;

class WeddingCostEstimateListItemDtoMapper {

  public static function map(
      ?WeddingCostEstimate $weddingCostEstimate): ?WeddingCostEstimateListItemDto {
    if ($weddingCostEstimate === null) {
      return null;
    }

    return new WeddingCostEstimateListItemDto(
        $weddingCostEstimate->getId(),
        $weddingCostEstimate->getName(),
        $weddingCostEstimate->getEstimate(),
        $weddingCostEstimate->getReal());
  }
}