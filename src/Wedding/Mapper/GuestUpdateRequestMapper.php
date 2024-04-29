<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\GuestUpdateRequest;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\GuestGroup;

class GuestUpdateRequestMapper {

  public static function map(?Guest $guest): ?GuestUpdateRequest {
    if ($guest === null) {
      return null;
    }

    return (new GuestUpdateRequest())
        ->setFirstName($guest->getFirstName())
        ->setLastName($guest->getLastName())
        ->setType($guest->getType())
        ->setInvited($guest->isInvited())
        ->setConfirmed($guest->isConfirmed())
        ->setAccommodation($guest->isAccommodation())
        ->setTransport($guest->isTransport())
        ->setDietType($guest->getDietType())
        ->setNote($guest->getNote())
        ->setTelephone($guest->getTelephone())
        ->setEmail($guest->getEmail())
        ->setPayment($guest->getPayment())
        ->setGroups(self::groups($guest->getGroups()->toArray()));
  }

  /**
   * @param array<GuestGroup> $guestGroups
   *
   * @return array<int>
   */
  private static function groups(array $guestGroups): array {
    return array_map(fn (GuestGroup $guestGroup) => $guestGroup->getId(), $guestGroups);
  }
}