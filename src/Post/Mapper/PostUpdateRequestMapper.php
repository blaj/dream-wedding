<?php

namespace App\Post\Mapper;

use App\FileStorage\Entity\LocalFileResource;
use App\FileStorage\Service\FileStorageService;
use App\Post\Dto\PostUpdateRequest;
use App\Post\Entity\Post;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PostUpdateRequestMapper {

  public static function map(?Post $post): ?PostUpdateRequest {
    if ($post === null) {
      return null;
    }

    return (new PostUpdateRequest())
        ->setTitle($post->getTitle())
        ->setShortContent($post->getShortContent())
        ->setContent($post->getContent())
        ->setHeadingImagePath($post->getHeadingImage()?->getPath());
  }
}