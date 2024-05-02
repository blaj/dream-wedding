<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskListItemDto;
use App\Wedding\Entity\Task;

class TaskListItemDtoMapper {

  public static function map(?Task $task): ?TaskListItemDto {
    if ($task === null) {
      return null;
    }

    return new TaskListItemDto($task->getId(), $task->getName(), $task->getOnDate());
  }
}