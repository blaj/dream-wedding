<?php

namespace App\Wedding\Dto;

readonly class TaskGroupDetailsDto {

  /**
   * @param array<string> $taskNames
   */
  public function __construct(public int $id, public string $name, public array $taskNames) {}
}