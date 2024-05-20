<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\DietType;
use App\Wedding\Entity\Enum\GuestType;

class GuestListFilterRequest {

  private ?string $firstName = null;

  private ?string $lastName = null;

  private ?GuestType $type = null;

  private ?bool $invited = null;

  private ?bool $confirmed = null;

  private ?bool $accommodation = null;

  private ?bool $transport = null;

  private ?DietType $dietType = null;

  public function getFirstName(): ?string {
    return $this->firstName;
  }

  public function setFirstName(?string $firstName): self {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string {
    return $this->lastName;
  }

  public function setLastName(?string $lastName): self {
    $this->lastName = $lastName;

    return $this;
  }

  public function getType(): ?GuestType {
    return $this->type;
  }

  public function setType(?GuestType $type): self {
    $this->type = $type;

    return $this;
  }

  public function getInvited(): ?bool {
    return $this->invited;
  }

  public function setInvited(?bool $invited): self {
    $this->invited = $invited;

    return $this;
  }

  public function getConfirmed(): ?bool {
    return $this->confirmed;
  }

  public function setConfirmed(?bool $confirmed): self {
    $this->confirmed = $confirmed;

    return $this;
  }

  public function getAccommodation(): ?bool {
    return $this->accommodation;
  }

  public function setAccommodation(?bool $accommodation): self {
    $this->accommodation = $accommodation;

    return $this;
  }

  public function getTransport(): ?bool {
    return $this->transport;
  }

  public function setTransport(?bool $transport): self {
    $this->transport = $transport;

    return $this;
  }

  public function getDietType(): ?DietType {
    return $this->dietType;
  }

  public function setDietType(?DietType $dietType): self {
    $this->dietType = $dietType;

    return $this;
  }
}