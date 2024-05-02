<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskUpdateRequest;
use App\Wedding\Entity\Task;

class TaskUpdateRequestMapper {

  public static function map(?Task $task): ?TaskUpdateRequest {
    if ($task === null) {
      return null;
    }

    return (new TaskUpdateRequest())
        ->setName($task->getName())
        ->setDescription($task->getDescription())
        ->setOnDate($task->getOnDate());
  }
}