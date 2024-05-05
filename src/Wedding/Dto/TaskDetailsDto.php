<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;

readonly class TaskDetailsDto {

  public function __construct(
      public int $id,
      public string $name,
      public ?string $description,
      public ?DateTimeImmutable $onDate,
      public ?string $color,
      public bool $completed) {}
}