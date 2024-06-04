<?php

namespace App\Post\Dto;

readonly class PostTagListItemDto {

  public function __construct(public int $id, public string $name) {}
}