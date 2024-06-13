<?php

namespace App\Post\Dto;

use DateTimeImmutable;

readonly class PostListItemDto {

  /**
   * @param array<string> $categoryNames
   */
  public function __construct(
      public int $id,
      public string $title,
      public string $content,
      public string $shortContent,
      public array $categoryNames,
      public DateTimeImmutable $createdAt,
      public ?string $createdByName,
      public ?string $headingImagePath) {}
}