<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateDetailsDto;
use App\Wedding\Entity\CostEstimate;

class CostEstimateDetailsDtoMapper {

  public static function map(
      ?CostEstimate $costEstimate): ?CostEstimateDetailsDto {
    if ($costEstimate === null) {
      return null;
    }

    return new CostEstimateDetailsDto(
        $costEstimate->getId(),
        $costEstimate->getName(),
        $costEstimate->getDescription(),
        $costEstimate->getCost(),
        $costEstimate->getAdvancePayment(),
        $costEstimate->getQuantity(),
        $costEstimate->getUnitType(),
        $costEstimate->isDependsOnGuests(),
        $costEstimate->getGroup()?->getName(),
        $costEstimate->getOrderNo(),
        $costEstimate->getPaid());
  }
}