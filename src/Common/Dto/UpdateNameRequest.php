<?php

namespace App\Common\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

readonly class UpdateNameRequest {

  public function __construct(
      #[NotBlank] #[Length(max: 200)] public string $name) {}
}