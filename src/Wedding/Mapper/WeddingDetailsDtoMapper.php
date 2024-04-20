<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingDetailsDto;
use App\Wedding\Entity\Wedding;

class WeddingDetailsDtoMapper {

  public static function map(?Wedding $wedding): ?WeddingDetailsDto {
    if ($wedding === null) {
      return null;
    }

    return new WeddingDetailsDto($wedding->getId(), $wedding->getName());
  }
}