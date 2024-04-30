<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\TableType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TableUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  private TableType $type;

  #[GreaterThanOrEqual(1)]
  private int $numberOfSeats = 1;

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

  public function getType(): TableType {
    return $this->type;
  }

  public function setType(TableType $type): self {
    $this->type = $type;

    return $this;
  }

  public function getNumberOfSeats(): int {
    return $this->numberOfSeats;
  }

  public function setNumberOfSeats(int $numberOfSeats): self {
    $this->numberOfSeats = $numberOfSeats;

    return $this;
  }
}