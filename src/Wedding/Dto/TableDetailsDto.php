<?php

namespace App\Wedding\Dto;

readonly class TableDetailsDto {

  public function __construct(public int $id, public string $name, public ?string $description) {}
}