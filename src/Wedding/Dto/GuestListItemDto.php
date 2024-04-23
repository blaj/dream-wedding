<?php

namespace App\Wedding\Dto;

readonly class GuestListItemDto {

  public function __construct(public int $id, public string $firstName, public string $lastName) {}
}