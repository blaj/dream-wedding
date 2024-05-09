<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TableUpdateRequest;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\Table;

class TableUpdateRequestMapper {

  public static function map(?Table $table): ?TableUpdateRequest {
    if ($table === null) {
      return null;
    }

    return (new TableUpdateRequest())
        ->setName($table->getName())
        ->setDescription($table->getDescription())
        ->setType($table->getType())
        ->setNumberOfSeats($table->getNumberOfSeats())
        ->setGuests(self::guests($table->getGuests()->toArray()));
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