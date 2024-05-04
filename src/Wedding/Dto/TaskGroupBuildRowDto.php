<?php

namespace App\Wedding\Dto;

class TaskGroupBuildRowDto {

  private TaskGroupListItemDto $taskGroupListItemDto;

  /**
   * @var array<TaskListItemDto>
   */
  private array $tasksListItemDto = [];

  public function getTaskGroupListItemDto(): TaskGroupListItemDto {
    return $this->taskGroupListItemDto;
  }

  public function setTaskGroupListItemDto(
      TaskGroupListItemDto $taskGroupListItemDto): self {
    $this->taskGroupListItemDto = $taskGroupListItemDto;

    return $this;
  }

  /**
   * @return array<TaskListItemDto>
   */
  public function getTasksListItemDto(): array {
    return $this->tasksListItemDto;
  }

  /**
   * @param array<TaskListItemDto> $tasksListItemDto
   */
  public function setTasksListItemDto(array $tasksListItemDto): self {
    $this->tasksListItemDto = $tasksListItemDto;

    return $this;
  }

  public function addTaskListItemDto(TaskListItemDto $taskListItemDto): self {
    $this->tasksListItemDto[] = $taskListItemDto;

    return $this;
  }
}