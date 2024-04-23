<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;

readonly class WeddingListItemDto {

  public function __construct(
      public int $id,
      public string $name,
      public DateTimeImmutable $onDate) {}
}