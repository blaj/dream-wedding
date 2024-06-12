<?php

namespace App\FileStorage\Service;

use App\FileStorage\Dto\LocalFileResourceWrapper;
use App\FileStorage\Entity\LocalFileResource;
use App\FileStorage\Repository\LocalFileResourceRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class HeadingImageService {

  public function __construct(
      private readonly FileStorageService $fileStorageService,
      private readonly LocalFileResourceRepository $localFileResourceRepository) {}

  public function addAndGetHeadingImage(?UploadedFile $uploadedFile): ?LocalFileResourceWrapper {
    if ($uploadedFile === null) {
      return null;
    }

    $path = $this->fileStorageService->postPath();
    $size = $uploadedFile->getSize();
    $fullPath = $this->fileStorageService->uploadFile($uploadedFile, $path);

    $headingImage = (new LocalFileResource())
        ->setContentType($uploadedFile->getClientMimeType())
        ->setPath($fullPath)
        ->setOriginalFileName($uploadedFile->getClientOriginalName())
        ->setSize($size);
    $this->localFileResourceRepository->save($headingImage);

    return new LocalFileResourceWrapper($headingImage, $fullPath);
  }
}