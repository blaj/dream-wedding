<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestGroupDetailsDto;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\GuestGroup;

class GuestGroupDetailsDtoMapper {

  public static function map(?GuestGroup $guestGroup): ?GuestGroupDetailsDto {
    if ($guestGroup === null) {
      return null;
    }

    return new GuestGroupDetailsDto(
        $guestGroup->getId(),
        $guestGroup->getName(),
        $guestGroup->getDescription(),
        self::guestNames($guestGroup->getGuests()->toArray()));
  }

  /**
   * @param array<Guest> $guests
   *
   * @return array<string>
   */
  private static function guestNames(array $guests): array {
    return array_map(
        fn (Guest $guest) => $guest->getFirstName() . ' ' . $guest->getLastName(),
        $guests);
  }
}