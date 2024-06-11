<?php

namespace App\Offer\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OfferUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $title;

  #[NotBlank]
  private string $content;

  /**
   * @var array<int>
   */
  private array $categories = [];

  public function getTitle(): string {
    return $this->title;
  }

  public function setTitle(string $title): self {
    $this->title = $title;

    return $this;
  }

  public function getContent(): string {
    return $this->content;
  }

  public function setContent(string $content): self {
    $this->content = $content;

    return $this;
  }

  /**
   * @return array<int>
   */
  public function getCategories(): array {
    return $this->categories;
  }

  /**
   * @param array<int> $categories
   */
  public function setCategories(array $categories): self {
    $this->categories = $categories;

    return $this;
  }
}