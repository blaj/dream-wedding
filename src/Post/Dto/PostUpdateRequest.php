<?php

namespace App\Post\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $title;

  #[NotBlank]
  private string $content;

  #[Image]
  private ?UploadedFile $headingImage = null;

  #[NotBlank]
  private string $shortContent;

  // TODO: not belong to request!
  private ?string $headingImagePath = null;

  /**
   * @var array<int>
   */
  private array $categories = [];

  /**
   * @var array<int>
   */
  private array $tags = [];

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

  public function getHeadingImage(): ?UploadedFile {
    return $this->headingImage;
  }

  public function setHeadingImage(?UploadedFile $headingImage): self {
    $this->headingImage = $headingImage;

    return $this;
  }

  public function getShortContent(): string {
    return $this->shortContent;
  }

  public function setShortContent(string $shortContent): self {
    $this->shortContent = $shortContent;

    return $this;
  }

  public function getHeadingImagePath(): ?string {
    return $this->headingImagePath;
  }

  public function setHeadingImagePath(?string $headingImagePath): self {
    $this->headingImagePath = $headingImagePath;

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

  /**
   * @return array<int>
   */
  public function getTags(): array {
    return $this->tags;
  }

  /**
   * @param array<int> $tags
   */
  public function setTags(array $tags): self {
    $this->tags = $tags;

    return $this;
  }
}