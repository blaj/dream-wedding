<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskCreateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  private ?DateTimeImmutable $onDate;

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(?string $description): self {
    $this->description = $description;

    return $this;
  }

  public function getOnDate(): ?DateTimeImmutable {
    return $this->onDate;
  }

  public function setOnDate(?DateTimeImmutable $onDate): self {
    $this->onDate = $onDate;

    return $this;
  }
}