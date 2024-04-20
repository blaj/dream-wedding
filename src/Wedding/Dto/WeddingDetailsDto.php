<?php

namespace App\Wedding\Dto;

readonly class WeddingDetailsDto {

  public function __construct(public int $id, public string $name) {}
}