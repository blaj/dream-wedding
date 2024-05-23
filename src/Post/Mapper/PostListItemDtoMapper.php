<?php

namespace App\Post\Mapper;

use App\Post\Dto\PostListItemDto;
use App\Post\Entity\Post;

class PostListItemDtoMapper {

  public static function map(?Post $post): ?PostListItemDto {
    if ($post === null) {
      return null;
    }

    return new PostListItemDto($post->getId(), $post->getTitle(), $post->getContent());
  }
}