<?php

namespace App\Wedding\Dto;

use App\Common\Dto\AddressRequest;
use DateTimeImmutable;
use Money\Money;

class WeddingUpdateRequest {

  private string $name;

  private DateTimeImmutable $onDate;

  private Money $budget;

  private AddressRequest $weddingAddress;

  private AddressRequest $partyAddress;

  public function __construct() {
    $this->budget = Money::PLN(0);
    $this->weddingAddress = new AddressRequest();
    $this->partyAddress = new AddressRequest();
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

  public function getWeddingAddress(): AddressRequest {
    return $this->weddingAddress;
  }

  public function setWeddingAddress(AddressRequest $weddingAddress): self {
    $this->weddingAddress = $weddingAddress;

    return $this;
  }

  public function getPartyAddress(): AddressRequest {
    return $this->partyAddress;
  }

  public function setPartyAddress(AddressRequest $partyAddress): self {
    $this->partyAddress = $partyAddress;

    return $this;
  }
}