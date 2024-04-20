<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingListItemDto;
use App\Wedding\Entity\Wedding;

class WeddingListItemDtoMapper {

  public static function map(?Wedding $wedding): ?WeddingListItemDto {
    if ($wedding === null) {
      return null;
    }

    return new WeddingListItemDto($wedding->getId(), $wedding->getName());
  }
}