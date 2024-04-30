<?php

namespace App\Wedding\Dto;

readonly class TableListItemDto {

  public function __construct(public int $id, public string $name, public int $numberOfSeats) {}
}