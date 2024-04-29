<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingCostEstimateUpdateRequest;
use App\Wedding\Entity\WeddingCostEstimate;

class WeddingCostEstimateUpdateRequestMapper {

  public static function map(
      ?WeddingCostEstimate $weddingCostEstimate): ?WeddingCostEstimateUpdateRequest {
    if ($weddingCostEstimate === null) {
      return null;
    }

    return (new WeddingCostEstimateUpdateRequest())
        ->setName($weddingCostEstimate->getName())
        ->setDescription($weddingCostEstimate->getDescription())
        ->setEstimate($weddingCostEstimate->getEstimate())
        ->setReal($weddingCostEstimate->getReal())
        ->setQuantity($weddingCostEstimate->getQuantity())
        ->setUnitType($weddingCostEstimate->getUnitType())
        ->setDependsOnGuests($weddingCostEstimate->isDependsOnGuests());
  }
}