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
        self::groupNames($guest->getGroups()->toArray()),
        $guest->getTable()?->getName());
  }

  /**
   * @param array<GuestGroup> $guestGroups
   *
   * @return array<string>
   */
  private static function groupNames(array $guestGroups): array {
    return array_map(fn (GuestGroup $guestGroup) => $guestGroup->getName(), $guestGroups);
  }
}