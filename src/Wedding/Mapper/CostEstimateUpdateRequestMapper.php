<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateUpdateRequest;
use App\Wedding\Entity\CostEstimate;

class CostEstimateUpdateRequestMapper {

  public static function map(
      ?CostEstimate $costEstimate): ?CostEstimateUpdateRequest {
    if ($costEstimate === null) {
      return null;
    }

    return (new CostEstimateUpdateRequest())
        ->setName($costEstimate->getName())
        ->setDescription($costEstimate->getDescription())
        ->setCost($costEstimate->getCost())
        ->setAdvancePayment($costEstimate->getAdvancePayment())
        ->setQuantity($costEstimate->getQuantity())
        ->setUnitType($costEstimate->getUnitType())
        ->setDependsOnGuests($costEstimate->isDependsOnGuests())
        ->setGroup($costEstimate->getGroup()?->getId())
        ->setOrderNo($costEstimate->getOrderNo())
        ->setPaid($costEstimate->getPaid());
  }
}