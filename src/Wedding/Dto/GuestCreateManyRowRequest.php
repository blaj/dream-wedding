<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\GuestType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GuestCreateManyRowRequest {

  #[NotBlank]
  #[Length(max: 100)]
  private string $firstName;

  #[NotBlank]
  #[Length(max: 100)]
  private string $lastName;

  private GuestType $type;

  public function getFirstName(): string {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): string {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;

    return $this;
  }

  public function getType(): GuestType {
    return $this->type;
  }

  public function setType(GuestType $type): self {
    $this->type = $type;

    return $this;
  }
}