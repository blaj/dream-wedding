<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TableDetailsDto;
use App\Wedding\Entity\Table;

class TableDetailsDtoMapper {

  public static function map(?Table $table): ?TableDetailsDto {
    if ($table === null) {
      return null;
    }

    return new TableDetailsDto($table->getId(), $table->getName(), $table->getDescription());
  }
}