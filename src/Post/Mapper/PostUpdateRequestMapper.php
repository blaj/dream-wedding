<?php

namespace App\Post\Mapper;

use App\FileStorage\Entity\LocalFileResource;
use App\FileStorage\Service\FileStorageService;
use App\Post\Dto\PostUpdateRequest;
use App\Post\Entity\Post;
use App\Post\Entity\PostCategory;
use App\Post\Entity\PostTag;
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
        ->setHeadingImagePath($post->getHeadingImage()?->getPath())
        ->setCategories(self::categories($post->getCategories()->toArray()))
        ->setTags(self::tags($post->getTags()->toArray()));
  }

  /**
   * @param array<PostCategory> $categories
   *
   * @return array<int>
   */
  private static function categories(array $categories): array {
    return array_map(fn (PostCategory $postCategory) => $postCategory->getId(), $categories);
  }

  /**
   * @param array<PostTag> $tags
   *
   * @return array<int>
   */
  private static function tags(array $tags): array {
    return array_map(fn (PostTag $postTag) => $postTag->getId(), $tags);
  }
}