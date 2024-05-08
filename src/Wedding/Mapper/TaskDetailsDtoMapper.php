<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskDetailsDto;
use App\Wedding\Entity\Task;

class TaskDetailsDtoMapper {

  public static function map(?Task $task): ?TaskDetailsDto {
    if ($task === null) {
      return null;
    }

    return new TaskDetailsDto(
        $task->getId(),
        $task->getName(),
        $task->getDescription(),
        $task->getOnDate(),
        $task->getColor(),
        $task->isCompleted(),
        $task->getOrderNo());
  }
}