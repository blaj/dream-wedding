<?php

namespace App\Post\Dto;

readonly class PostListItemDto {

  public function __construct(public int $id, public string $title, public string $content) {}
}