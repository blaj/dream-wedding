<?php

namespace App\Wedding\Dto;

readonly class GuestGroupListItemDto {

  public function __construct(public int $id, public string $name) {}
}