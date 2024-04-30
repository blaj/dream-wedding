<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TableUpdateRequest;
use App\Wedding\Entity\Table;

class TableUpdateRequestMapper {

  public static function map(?Table $table): ?TableUpdateRequest {
    if ($table === null) {
      return null;
    }

    return (new TableUpdateRequest())
        ->setName($table->getName())
        ->setDescription($table->getDescription())
        ->setType($table->getType());
  }
}