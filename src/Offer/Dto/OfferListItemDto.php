<?php

namespace App\Offer\Dto;

readonly class OfferListItemDto {

  /**
   * @param array<string> $categoryNames
   */
  public function __construct(
      public int $id,
      public string $title,
      public string $content,
      public string $shortContent,
      public ?string $headingImagePath,
      public array $categoryNames) {}
}