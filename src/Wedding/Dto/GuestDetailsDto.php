<?php

namespace App\Wedding\Dto;

readonly class GuestDetailsDto {

  public function __construct(public int $id, public string $firstName, public string $lastName) {}
}