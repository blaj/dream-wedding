<?php

namespace App\Common\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Address {

  #[Column(name: 'city', type: Types::STRING, length: 100, nullable: true)]
  private ?string $city = null;

  #[Column(name: 'street', type: Types::STRING, length: 100, nullable: true)]
  private ?string $street = null;

  #[Column(name: 'postcode', type: Types::STRING, length: 7, nullable: true)]
  private ?string $postcode = null;

  public function getCity(): ?string {
    return $this->city;
  }

  public function setCity(?string $city): Address {
    $this->city = $city;

    return $this;
  }

  public function getStreet(): ?string {
    return $this->street;
  }

  public function setStreet(?string $street): Address {
    $this->street = $street;

    return $this;
  }

  public function getPostcode(): ?string {
    return $this->postcode;
  }

  public function setPostcode(?string $postcode): Address {
    $this->postcode = $postcode;

    return $this;
  }
}