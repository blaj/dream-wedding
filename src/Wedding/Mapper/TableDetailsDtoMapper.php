<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TableDetailsDto;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\Table;

class TableDetailsDtoMapper {

  public static function map(?Table $table): ?TableDetailsDto {
    if ($table === null) {
      return null;
    }

    return new TableDetailsDto(
        $table->getId(),
        $table->getName(),
        $table->getType(),
        $table->getDescription(),
        $table->getNumberOfSeats(),
        $table->getGuests()->count(),
        self::guestNames($table->getGuests()->toArray()));
  }

  /**
   * @param array<Guest> $guests
   *
   * @return array<string>
   */
  private static function guestNames(array $guests): array {
    return array_map(fn (Guest $guest) => $guest->getFirstName() . ' ' . $guest->getLastName(),
        $guests);
  }
}