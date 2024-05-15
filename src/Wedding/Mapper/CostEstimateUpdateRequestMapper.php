<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateUpdateRequest;
use App\Wedding\Entity\CostEstimate;

class CostEstimateUpdateRequestMapper {

  public static function map(
      ?CostEstimate $weddingCostEstimate): ?CostEstimateUpdateRequest {
    if ($weddingCostEstimate === null) {
      return null;
    }

    return (new CostEstimateUpdateRequest())
        ->setName($weddingCostEstimate->getName())
        ->setDescription($weddingCostEstimate->getDescription())
        ->setEstimate($weddingCostEstimate->getEstimate())
        ->setReal($weddingCostEstimate->getReal())
        ->setQuantity($weddingCostEstimate->getQuantity())
        ->setUnitType($weddingCostEstimate->getUnitType())
        ->setDependsOnGuests($weddingCostEstimate->isDependsOnGuests())
        ->setGroup($weddingCostEstimate->getGroup()?->getId())
        ->setOrderNo($weddingCostEstimate->getOrderNo());
  }
}