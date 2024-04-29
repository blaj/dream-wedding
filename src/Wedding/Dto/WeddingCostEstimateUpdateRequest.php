<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\UnitType;
use Money\Money;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class WeddingCostEstimateUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  private Money $estimate;

  private Money $real;


  #[GreaterThanOrEqual(1)]
  private int $quantity = 1;

  private UnitType $unitType = UnitType::PIECE;

  private bool $dependsOnGuests = false;

  public function __construct() {
    $this->estimate = Money::PLN(0);
    $this->real = Money::PLN(0);
  }

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

  public function getEstimate(): Money {
    return $this->estimate;
  }

  public function setEstimate(Money $estimate): self {
    $this->estimate = $estimate;

    return $this;
  }

  public function getReal(): Money {
    return $this->real;
  }

  public function setReal(Money $real): self {
    $this->real = $real;

    return $this;
  }

  public function getQuantity(): int {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): self {
    $this->quantity = $quantity;

    return $this;
  }

  public function getUnitType(): UnitType {
    return $this->unitType;
  }

  public function setUnitType(UnitType $unitType): self {
    $this->unitType = $unitType;

    return $this;
  }

  public function isDependsOnGuests(): bool {
    return $this->dependsOnGuests;
  }

  public function setDependsOnGuests(bool $dependsOnGuests): self {
    $this->dependsOnGuests = $dependsOnGuests;

    return $this;
  }
}