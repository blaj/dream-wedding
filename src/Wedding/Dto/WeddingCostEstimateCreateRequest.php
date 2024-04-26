<?php

namespace App\Wedding\Dto;

use Money\Money;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class WeddingCostEstimateCreateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  private Money $estimate;

  private Money $real;

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
}