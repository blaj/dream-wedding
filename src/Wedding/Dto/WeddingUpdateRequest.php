<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;
use Money\Money;

class WeddingUpdateRequest {

  private string $name;

  private DateTimeImmutable $onDate;

  private Money $budget;

  public function __construct() {
    $this->budget = Money::PLN(0);
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getOnDate(): DateTimeImmutable {
    return $this->onDate;
  }

  public function setOnDate(DateTimeImmutable $onDate): self {
    $this->onDate = $onDate;

    return $this;
  }

  public function getBudget(): Money {
    return $this->budget;
  }

  public function setBudget(Money $budget): self {
    $this->budget = $budget;

    return $this;
  }
}