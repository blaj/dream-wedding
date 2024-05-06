<?php

namespace App\Wedding\Dto;

use App\Common\Dto\AddressDetailsDto;
use DateTimeImmutable;
use Money\Money;

readonly class WeddingDetailsDto {

  public function __construct(
      public int $id,
      public string $name,
      public DateTimeImmutable $onDate,
      public Money $budget,
      public ?AddressDetailsDto $weddingAddress,
      public ?AddressDetailsDto $partyAddress) {}
}