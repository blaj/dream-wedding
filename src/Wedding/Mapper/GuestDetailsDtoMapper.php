<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestDetailsDto;
use App\Wedding\Entity\Guest;

class GuestDetailsDtoMapper {

  public static function map(?Guest $guest): ?GuestDetailsDto {
    if ($guest === null) {
      return null;
    }

    return new GuestDetailsDto($guest->getId(), $guest->getFirstName(), $guest->getLastName());
  }
}