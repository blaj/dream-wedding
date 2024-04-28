<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestGroupDetailsDto;
use App\Wedding\Entity\GuestGroup;

class GuestGroupDetailsDtoMapper {

  public static function map(?GuestGroup $guestGroup): ?GuestGroupDetailsDto {
    if ($guestGroup === null) {
      return null;
    }

    return new GuestGroupDetailsDto(
        $guestGroup->getId(),
        $guestGroup->getName(),
        $guestGroup->getDescription());
  }
}