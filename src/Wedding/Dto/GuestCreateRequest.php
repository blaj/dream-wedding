<?php

namespace App\Wedding\Dto;

use App\Wedding\Entity\Enum\DietType;
use App\Wedding\Entity\Enum\GuestType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class GuestCreateRequest {

  #[NotBlank]
  #[Length(max: 100)]
  private string $firstName;

  #[NotBlank]
  #[Length(max: 100)]
  private string $lastName;

  private GuestType $type;

  private bool $invited;

  private bool $confirmed;

  private bool $accommodation;

  private bool $transport;

  private DietType $dietType;

  private ?string $note = null;

  #[Length(min: 9, max: 9)]
  private ?string $telephone = null;

  #[Email]
  private ?string $email = null;

  #[Range(min: 0)]
  private int $payment = 100;

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

  public function isInvited(): bool {
    return $this->invited;
  }

  public function setInvited(bool $invited): self {
    $this->invited = $invited;

    return $this;
  }

  public function isConfirmed(): bool {
    return $this->confirmed;
  }

  public function setConfirmed(bool $confirmed): self {
    $this->confirmed = $confirmed;

    return $this;
  }

  public function isAccommodation(): bool {
    return $this->accommodation;
  }

  public function setAccommodation(bool $accommodation): self {
    $this->accommodation = $accommodation;

    return $this;
  }

  public function isTransport(): bool {
    return $this->transport;
  }

  public function setTransport(bool $transport): self {
    $this->transport = $transport;

    return $this;
  }

  public function getDietType(): DietType {
    return $this->dietType;
  }

  public function setDietType(DietType $dietType): self {
    $this->dietType = $dietType;

    return $this;
  }

  public function getNote(): ?string {
    return $this->note;
  }

  public function setNote(?string $note): self {
    $this->note = $note;

    return $this;
  }

  public function getTelephone(): ?string {
    return $this->telephone;
  }

  public function setTelephone(?string $telephone): self {
    $this->telephone = $telephone;

    return $this;
  }

  public function getEmail(): ?string {
    return $this->email;
  }

  public function setEmail(?string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getPayment(): int {
    return $this->payment;
  }

  public function setPayment(int $payment): self {
    $this->payment = $payment;

    return $this;
  }
}