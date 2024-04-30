<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\TableGuestBuildDto;
use App\Wedding\Dto\TableGuestBuildRowDto;
use App\Wedding\Dto\TableListItemDto;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Repository\TableRepository;

class TableGuestBuilderService {

  public function __construct(private readonly TableRepository $tableRepository) {}

  public function build(int $weddingId, int $userId): TableGuestBuildDto {
    $tablesGuestsBuildRowDto = [];

    $tables = $this->tableRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($tables as $table) {
      if (!array_key_exists($table->getId(), $tablesGuestsBuildRowDto)) {
        $tablesGuestsBuildRowDto[$table->getId()] =
            (new TableGuestBuildRowDto())
                ->setTableListItemDto(new TableListItemDto($table->getId(), $table->getName()));
      }

      foreach ($table->getGuests() as $guest) {
        $guestListItemDto = GuestListItemDtoMapper::map($guest);

        if ($guestListItemDto === null) {
          continue;
        }

        $tablesGuestsBuildRowDto[$table->getId()]->addGuestListItemDto($guestListItemDto);
      }
    }

    return new TableGuestBuildDto($tablesGuestsBuildRowDto);
  }
}