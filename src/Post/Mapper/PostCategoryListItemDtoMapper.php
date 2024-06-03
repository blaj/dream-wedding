<?php

namespace App\Post\Mapper;

use App\Post\Dto\PostCategoryListItemDto;
use App\Post\Entity\PostCategory;

class PostCategoryListItemDtoMapper {

  public static function map(?PostCategory $postCategory): ?PostCategoryListItemDto {
    if ($postCategory === null) {
      return null;
    }

    return new PostCategoryListItemDto($postCategory->getId(), $postCategory->getName());
  }
}