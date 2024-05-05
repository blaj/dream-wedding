<?php

namespace App\Wedding\Dto;

use Symfony\Component\Validator\Constraints\CssColor;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskGroupUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  /**
   * @var array<int>
   */
  private array $tasks = [];

  private bool $setColor = false;

  #[CssColor]
  private ?string $color = '#ffffff';

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  /**
   * @return array<int>
   */
  public function getTasks(): array {
    return $this->tasks;
  }

  /**
   * @param array<int> $tasks
   */
  public function setTasks(array $tasks): self {
    $this->tasks = $tasks;

    return $this;
  }

  public function isSetColor(): bool {
    return $this->setColor;
  }

  public function setSetColor(bool $setColor): self {
    $this->setColor = $setColor;

    return $this;
  }

  public function getColor(): ?string {
    return $this->color;
  }

  public function setColor(?string $color): self {
    $this->color = $color;

    return $this;
  }
}