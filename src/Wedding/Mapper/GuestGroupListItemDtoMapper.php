<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestGroupListItemDto;
use App\Wedding\Entity\GuestGroup;

class GuestGroupListItemDtoMapper {

  public static function map(?GuestGroup $guestGroup): ?GuestGroupListItemDto {
    if ($guestGroup === null) {
      return null;
    }

    return new GuestGroupListItemDto($guestGroup->getId(), $guestGroup->getName());
  }
}