<?php

namespace App\Wedding\Dto;

readonly class TaskGroupBuildDto {

  /**
   * @param array<TaskGroupBuildRowDto> $tasksGroupsBuildRowDto
   */
  public function __construct(
      public array $tasksGroupsBuildRowDto,
      public int $tasksAmount,
      public int $completedAmount,
      public int $expiredAmount,
      public int $completedPercentage) {}
}