<?php

namespace App\Wedding\Dto;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\CssColor;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskCreateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  private ?DateTimeImmutable $onDate;

  private bool $setColor = false;

  #[CssColor]
  private ?string $color = '#ffffff';

  private bool $completed = false;

  private ?int $group = null;

  private int $orderNo = 0;

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(?string $description): self {
    $this->description = $description;

    return $this;
  }

  public function getOnDate(): ?DateTimeImmutable {
    return $this->onDate;
  }

  public function setOnDate(?DateTimeImmutable $onDate): self {
    $this->onDate = $onDate;

    return $this;
  }

  public function isSetColor(): bool {
    return $this->setColor;
  }

  public function setSetColor(bool $setColor): self {
    $this->setColor = $setColor;

    return $this;
  }

  public function getColor(): ?string {
    return $this->color;
  }

  public function setColor(?string $color): self {
    $this->color = $color;

    return $this;
  }

  public function isCompleted(): bool {
    return $this->completed;
  }

  public function setCompleted(bool $completed): self {
    $this->completed = $completed;

    return $this;
  }

  public function getGroup(): ?int {
    return $this->group;
  }

  public function setGroup(?int $group): self {
    $this->group = $group;

    return $this;
  }

  public function getOrderNo(): int {
    return $this->orderNo;
  }

  public function setOrderNo(int $orderNo): self {
    $this->orderNo = $orderNo;

    return $this;
  }
}