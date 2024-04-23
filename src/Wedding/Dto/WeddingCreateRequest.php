<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class WeddingCreateRequest {

  #[NotBlank]
  #[Length(max: 100)]
  private string $name;

  private DateTimeImmutable $onDate;

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
}