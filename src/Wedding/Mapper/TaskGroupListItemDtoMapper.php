<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskGroupListItemDto;
use App\Wedding\Entity\TaskGroup;

class TaskGroupListItemDtoMapper {

  public static function map(?TaskGroup $taskGroup): ?TaskGroupListItemDto {
    if ($taskGroup === null) {
      return null;
    }

    return new TaskGroupListItemDto($taskGroup->getId(), $taskGroup->getName());
  }
}