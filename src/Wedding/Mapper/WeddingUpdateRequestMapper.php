<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingUpdateRequest;
use App\Wedding\Entity\Wedding;

class WeddingUpdateRequestMapper {

  public static function map(?Wedding $wedding): ?WeddingUpdateRequest {
    if ($wedding === null) {
      return null;
    }

    return (new WeddingUpdateRequest())
        ->setName($wedding->getName())
        ->setOnDate($wedding->getOnDate())
        ->setBudget($wedding->getBudget());
  }
}