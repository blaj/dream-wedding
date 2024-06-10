<?php

namespace App\App\Post\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class UploadImageRequest {

  public function __construct(public UploadedFile $file) {}
}