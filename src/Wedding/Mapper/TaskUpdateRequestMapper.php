<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskUpdateRequest;
use App\Wedding\Entity\Task;

class TaskUpdateRequestMapper {

  public static function map(?Task $task): ?TaskUpdateRequest {
    if ($task === null) {
      return null;
    }

    $taskUpdateRequest = (new TaskUpdateRequest())
        ->setName($task->getName())
        ->setDescription($task->getDescription())
        ->setOnDate($task->getOnDate())
        ->setSetColor($task->getColor() !== null)
        ->setCompleted($task->isCompleted())
        ->setGroup($task->getGroup()?->getId())
        ->setOrderNo($task->getOrderNo());

    if ($task->getColor() !== null) {
      $taskUpdateRequest->setColor($task->getColor());
    }

    return $taskUpdateRequest;
  }
}