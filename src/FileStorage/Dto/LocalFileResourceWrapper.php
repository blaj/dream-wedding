<?php

namespace App\FileStorage\Dto;

use App\FileStorage\Entity\LocalFileResource;

readonly class LocalFileResourceWrapper {

  public function __construct(
      public LocalFileResource $localFileResource,
      public string $fullPath) {}
}