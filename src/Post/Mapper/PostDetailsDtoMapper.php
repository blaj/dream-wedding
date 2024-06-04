<?php

namespace App\Post\Mapper;

use App\Post\Dto\PostDetailsDto;
use App\Post\Entity\Post;
use App\Post\Entity\PostCategory;
use App\Post\Entity\PostTag;

class PostDetailsDtoMapper {

  public static function map(?Post $post): ?PostDetailsDto {
    if ($post === null) {
      return null;
    }

    return new PostDetailsDto(
        $post->getId(),
        $post->getTitle(),
        $post->getContent(),
        $post->getShortContent(),
        self::categoryNames($post->getCategories()->toArray()),
        self::tagNames($post->getTags()->toArray()),
        $post->getCreatedAt(),
        $post->getCreatedBy()?->getUsername(),
        $post->getHeadingImage()?->getPath());
  }

  /**
   * @param array<PostCategory> $categories
   *
   * @return array<string>
   */
  private static function categoryNames(array $categories): array {
    return array_map(fn (PostCategory $postCategory) => $postCategory->getName(), $categories);
  }

  /**
   * @param array<PostTag> $tags
   *
   * @return array<string>
   */
  private static function tagNames(array $tags): array {
    return array_map(fn (PostTag $postTag) => $postTag->getName(), $tags);
  }
}