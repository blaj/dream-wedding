<?php

namespace App\App\Post\Dto;

readonly class DeleteImageRequest {

  public function __construct(public string $path) {}
}