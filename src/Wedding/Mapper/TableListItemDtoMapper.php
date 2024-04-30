<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TableListItemDto;
use App\Wedding\Entity\Table;

class TableListItemDtoMapper {

  public static function map(?Table $table): ?TableListItemDto {
    if ($table === null) {
      return null;
    }

    return new TableListItemDto($table->getId(), $table->getName());
  }
}