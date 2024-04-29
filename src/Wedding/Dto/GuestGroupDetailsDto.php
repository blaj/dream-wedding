<?php

namespace App\Wedding\Dto;

readonly class GuestGroupDetailsDto {

  /**
   * @param array<string> $guestNames
   */
  public function __construct(
      public int $id,
      public string $name,
      public ?string $description,
      public array $guestNames) {}
}