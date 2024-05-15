<?php

namespace App\Common\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class GroupSimpleCreateRequest {

  #[NotBlank]
  #[Length(max: 200)]
  private string $name;

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }
}