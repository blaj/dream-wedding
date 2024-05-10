<?php

namespace App\Wedding\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CostEstimateGroupCreateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  private ?string $description = null;

  /**
   * @var array<int>
   */
  private array $costEstimates = [];

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

  /**
   * @return array<int>
   */
  public function getCostEstimates(): array {
    return $this->costEstimates;
  }

  /**
   * @param array<int> $costEstimates
   */
  public function setCostEstimates(array $costEstimates): self {
    $this->costEstimates = $costEstimates;

    return $this;
  }
}