<?php

namespace App\Post\Mapper;

use App\Post\Dto\PostTagListItemDto;
use App\Post\Entity\PostTag;

class PostTagListItemDtoMapper {

  public static function map(?PostTag $postTag): ?PostTagListItemDto {
    if ($postTag === null) {
      return null;
    }

    return new PostTagListItemDto($postTag->getId(), $postTag->getName());
  }
}