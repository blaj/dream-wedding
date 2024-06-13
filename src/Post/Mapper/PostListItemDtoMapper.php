<?php

namespace App\Post\Mapper;

use App\Post\Dto\PostListItemDto;
use App\Post\Entity\Post;
use App\Post\Entity\PostCategory;

class PostListItemDtoMapper {

  public static function map(?Post $post): ?PostListItemDto {
    if ($post === null) {
      return null;
    }

    return new PostListItemDto(
        $post->getId(),
        $post->getTitle(),
        $post->getContent(),
        $post->getShortContent(),
        self::categoryNames($post->getCategories()->toArray()),
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
}