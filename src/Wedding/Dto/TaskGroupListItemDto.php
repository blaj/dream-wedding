<?php

namespace App\Wedding\Dto;

readonly class TaskGroupListItemDto {

  public function __construct(public int $id, public string $name) {}
}