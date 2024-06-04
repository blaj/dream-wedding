<?php

namespace App\Post\Dto;

use DateTimeImmutable;

readonly class PostDetailsDto {

  /**
   * @param array<string> $categoryNames
   * @param array<string> $tagNames
   */
  public function __construct(
      public int $id,
      public string $title,
      public string $content,
      public string $shortContent,
      public array $categoryNames,
      public array $tagNames,
      public DateTimeImmutable $createdAt,
      public ?string $createdByName,
      public ?string $headingImagePath) {}
}