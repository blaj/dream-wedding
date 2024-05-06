<?php

namespace App\Common\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AddressRequest {

  #[NotBlank]
  #[Length(max: 100)]
  private ?string $city = null;

  #[NotBlank]
  #[Length(max: 100)]
  private ?string $street = null;

  #[NotBlank]
  #[Length(exactly: 6)]
  #[Regex(pattern: '/^[A-Z]{1,2}[0-9]{1,2}[A-Z]? [0-9][A-Z]{2}$/')]
  private ?string $postcode = null;

  public function getCity(): ?string {
    return $this->city;
  }

  public function setCity(?string $city): self {
    $this->city = $city;

    return $this;
  }

  public function getStreet(): ?string {
    return $this->street;
  }

  public function setStreet(?string $street): self {
    $this->street = $street;

    return $this;
  }

  public function getPostcode(): ?string {
    return $this->postcode;
  }

  public function setPostcode(?string $postcode): self {
    $this->postcode = $postcode;

    return $this;
  }
}