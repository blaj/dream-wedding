<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\UnitType;
use Money\Money;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CostEstimateUpdateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  private Money $cost;

  private Money $advancePayment;


  #[GreaterThanOrEqual(1)]
  private int $quantity = 1;

  private UnitType $unitType = UnitType::PIECE;

  private bool $dependsOnGuests = false;

  private ?int $group = null;

  private int $orderNo = 0;

  private Money $paid;

  public function __construct() {
    $this->cost = Money::PLN(0);
    $this->advancePayment = Money::PLN(0);
    $this->paid = Money::PLN(0);
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

  public function getCost(): Money {
    return $this->cost;
  }

  public function setCost(Money $cost): self {
    $this->cost = $cost;

    return $this;
  }

  public function getAdvancePayment(): Money {
    return $this->advancePayment;
  }

  public function setAdvancePayment(Money $advancePayment): self {
    $this->advancePayment = $advancePayment;

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

  public function getGroup(): ?int {
    return $this->group;
  }

  public function setGroup(?int $group): self {
    $this->group = $group;

    return $this;
  }

  public function getOrderNo(): int {
    return $this->orderNo;
  }

  public function setOrderNo(int $orderNo): self {
    $this->orderNo = $orderNo;

    return $this;
  }

  public function getPaid(): Money {
    return $this->paid;
  }

  public function setPaid(Money $paid): self {
    $this->paid = $paid;

    return $this;
  }
}