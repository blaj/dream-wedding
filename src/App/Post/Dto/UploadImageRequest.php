<?php

namespace App\App\Post\Dto;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageRequest {

  private UploadedFile $file;

  public function getFile(): UploadedFile {
    return $this->file;
  }

  public function setFile(UploadedFile $file): self {
    $this->file = $file;

    return $this;
  }
}