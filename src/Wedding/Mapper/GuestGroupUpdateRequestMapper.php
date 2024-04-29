<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestGroupUpdateRequest;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\GuestGroup;

class GuestGroupUpdateRequestMapper {

  public static function map(?GuestGroup $guestGroup): ?GuestGroupUpdateRequest {
    if ($guestGroup === null) {
      return null;
    }

    return (new GuestGroupUpdateRequest())
        ->setName($guestGroup->getName())
        ->setDescription($guestGroup->getDescription())
        ->setGuests(self::guests($guestGroup->getGuests()->toArray()));
  }

  /**
   * @param array<Guest> $guests
   *
   * @return array<int>
   */
  private static function guests(array $guests): array {
    return array_map(fn (Guest $guest) => $guest->getId(), $guests);
  }
}