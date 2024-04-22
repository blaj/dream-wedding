<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestListItemDto;
use App\Wedding\Entity\Guest;

class GuestListItemDtoMapper {

  public static function map(?Guest $guest): ?GuestListItemDto {
    if ($guest === null) {
      return null;
    }

    return new GuestListItemDto($guest->getId());
  }
}