<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskGroupUpdateRequest;
use App\Wedding\Entity\Task;
use App\Wedding\Entity\TaskGroup;

class TaskGroupUpdateRequestMapper {

  public static function map(?TaskGroup $taskGroup): ?TaskGroupUpdateRequest {
    if ($taskGroup === null) {
      return null;
    }

    $taskGroupUpdateRequest = (new TaskGroupUpdateRequest())
        ->setName($taskGroup->getName())
        ->setTasks(self::tasks($taskGroup->getTasks()->toArray()))
        ->setSetColor($taskGroup->getColor() !== null);

    if ($taskGroup->getColor() !== null) {
      $taskGroupUpdateRequest->setColor($taskGroup->getColor());
    }

    return $taskGroupUpdateRequest;
  }

  /**
   * @param array<Task> $tasks
   *
   * @return array<int>
   */
  private static function tasks(array $tasks): array {
    return array_map(fn (Task $task) => $task->getId(), $tasks);
  }
}