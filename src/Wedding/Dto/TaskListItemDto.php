<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;

readonly class TaskListItemDto {

  public function __construct(
      public int $id,
      public string $name,
      public ?DateTimeImmutable $onDate,
      public bool $completed,
      public int $orderNo) {}
}