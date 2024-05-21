<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateListItemDto;
use App\Wedding\Entity\CostEstimate;

class CostEstimateListItemDtoMapper {

  public static function map(
      ?CostEstimate $costEstimate): ?CostEstimateListItemDto {
    if ($costEstimate === null) {
      return null;
    }

    return new CostEstimateListItemDto(
        $costEstimate->getId(),
        $costEstimate->getName(),
        $costEstimate->getCost(),
        $costEstimate->getAdvancePayment(),
        $costEstimate->getQuantity(),
        $costEstimate->getUnitType(),
        $costEstimate->isDependsOnGuests(),
        $costEstimate->getPaid());
  }
}