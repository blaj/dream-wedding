<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\TableGuestBuildDto;
use App\Wedding\Dto\TableGuestBuildRowDto;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Mapper\TableListItemDtoMapper;
use App\Wedding\Repository\TableRepository;

class TableGuestBuilderService {

  public function __construct(private readonly TableRepository $tableRepository) {}

  public function build(int $weddingId, int $userId): TableGuestBuildDto {
    $tablesGuestsBuildRowDto = [];

    $tables = $this->tableRepository->findAllByWeddingIdAndUserId($weddingId, $userId);
    $guestsAmount = 0;

    foreach ($tables as $table) {
      if (!array_key_exists($table->getId(), $tablesGuestsBuildRowDto)) {
        $tableListItemDto = TableListItemDtoMapper::map($table);

        if ($tableListItemDto === null) {
          continue;
        }

        $tablesGuestsBuildRowDto[$table->getId()] =
            (new TableGuestBuildRowDto())->setTableListItemDto($tableListItemDto);
      }

      $guests = $table->getGuests();
      $guestsAmount += count($guests);

      foreach ($guests as $guest) {
        $guestListItemDto = GuestListItemDtoMapper::map($guest);

        if ($guestListItemDto === null) {
          continue;
        }

        $tablesGuestsBuildRowDto[$table->getId()]->addGuestListItemDto($guestListItemDto);
      }
    }

    $numberOfSeats =
        $this->tableRepository->findSumNumberOfSeatsByWeddingIdAndUserId($weddingId, $userId);

    $numberOfSeatsPercentage =
        $guestsAmount > 0
            ? (int) round($guestsAmount / $numberOfSeats * 100)
            : 0;

    return new TableGuestBuildDto(
        $tablesGuestsBuildRowDto,
        $numberOfSeats,
        $numberOfSeatsPercentage);
  }
}