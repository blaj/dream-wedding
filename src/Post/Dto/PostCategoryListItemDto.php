<?php

namespace App\Post\Dto;

readonly class PostCategoryListItemDto {

  public function __construct(public int $id, public string $name) {}
}