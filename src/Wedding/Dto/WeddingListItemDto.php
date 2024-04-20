<?php

namespace App\Wedding\Dto;

readonly class WeddingListItemDto {

  public function __construct(public int $id, public string $name) {}
}