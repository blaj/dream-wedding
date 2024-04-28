<?php

namespace App\Wedding\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GuestGroupUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

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
}