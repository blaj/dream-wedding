<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestGroupUpdateRequest;
use App\Wedding\Entity\GuestGroup;

class GuestGroupUpdateRequestMapper {

  public static function map(?GuestGroup $guestGroup): ?GuestGroupUpdateRequest {
    if ($guestGroup === null) {
      return null;
    }

    return (new GuestGroupUpdateRequest())
        ->setName($guestGroup->getName())
        ->setDescription($guestGroup->getDescription());
  }
}