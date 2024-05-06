<?php

namespace App\Common\Dto;

readonly class AddressDetailsDto {

  public function __construct(
      public ?string $city,
      public ?string $street,
      public ?string $postcode) {}
}