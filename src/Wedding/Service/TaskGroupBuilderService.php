<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\TaskGroupBuildDto;
use App\Wedding\Dto\TaskGroupBuildRowDto;
use App\Wedding\Mapper\TaskGroupListItemDtoMapper;
use App\Wedding\Mapper\TaskListItemDtoMapper;
use App\Wedding\Repository\TaskGroupRepository;

class TaskGroupBuilderService {

  public function __construct(private readonly TaskGroupRepository $taskGroupRepository) {}

  public function build(int $weddingId, int $userId): TaskGroupBuildDto {
    $tasksGroupsBuildRowDto = [];

    $taskGroups = $this->taskGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($taskGroups as $taskGroup) {
      if (!array_key_exists($taskGroup->getId(), $tasksGroupsBuildRowDto)) {
        $taskGroupListItemDto = TaskGroupListItemDtoMapper::map($taskGroup);

        if ($taskGroupListItemDto === null) {
          continue;
        }

        $tasksGroupsBuildRowDto[$taskGroup->getId()] =
            (new TaskGroupBuildRowDto())->setTaskGroupListItemDto($taskGroupListItemDto);
      }

      foreach ($taskGroup->getTasks() as $task) {
        $taskListItemDto = TaskListItemDtoMapper::map($task);

        if ($taskListItemDto === null) {
          continue;
        }

        $tasksGroupsBuildRowDto[$taskGroup->getId()]->addTaskListItemDto($taskListItemDto);
      }
    }

    return new TaskGroupBuildDto($tasksGroupsBuildRowDto);
  }
}