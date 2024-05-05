<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\TaskGroupDetailsDto;
use App\Wedding\Entity\Task;
use App\Wedding\Entity\TaskGroup;

class TaskGroupDetailsDtoMapper {

  public static function map(?TaskGroup $taskGroup): ?TaskGroupDetailsDto {
    if ($taskGroup === null) {
      return null;
    }

    return new TaskGroupDetailsDto(
        $taskGroup->getId(),
        $taskGroup->getName(),
        self::taskNames($taskGroup->getTasks()->toArray()),
        $taskGroup->getColor());
  }

  /**
   * @param array<Task> $tasks
   *
   * @return array<string>
   */
  private static function taskNames(array $tasks): array {
    return array_map(fn (Task $task) => $task->getName(), $tasks);
  }
}