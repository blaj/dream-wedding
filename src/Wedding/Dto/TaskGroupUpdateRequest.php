<?php

namespace App\Wedding\Dto;

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
}