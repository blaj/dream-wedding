<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestDetailsDto;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\GuestGroup;

class GuestDetailsDtoMapper {

  public static function map(?Guest $guest): ?GuestDetailsDto {
    if ($guest === null) {
      return null;
    }

    return new GuestDetailsDto(
        $guest->getId(),
        $guest->getFirstName(),
        $guest->getLastName(),
        $guest->getType(),
        $guest->isInvited(),
        $guest->isConfirmed(),
        $guest->isAccommodation(),
        $guest->isTransport(),
        $guest->getDietType(),
        $guest->getNote(),
        $guest->getTelephone(),
        $guest->getEmail(),
        $guest->getPayment(),
        $guest->getGroup()?->getName(),
        $guest->getTable()?->getName(),
        $guest->getGroupOrderNo(),
        $guest->getTableOrderNo());
  }
}