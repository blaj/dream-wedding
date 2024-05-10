<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\CostEstimateGroupListItemDto;
use App\Wedding\Entity\CostEstimateGroup;

class CostEstimateGroupListItemDtoMapper {

  public static function map(?CostEstimateGroup $costEstimateGroup): ?CostEstimateGroupListItemDto {
    if ($costEstimateGroup === null) {
      return null;
    }

    return new CostEstimateGroupListItemDto(
        $costEstimateGroup->getId(),
        $costEstimateGroup->getName());
  }
}