<?php

namespace App\Wedding\Dto;

readonly class TaskGroupBuildDto {

  /**
   * @param array<TaskGroupBuildRowDto> $tasksGroupsBuildRowDto
   */
  public function __construct(public array $tasksGroupsBuildRowDto) {}
}