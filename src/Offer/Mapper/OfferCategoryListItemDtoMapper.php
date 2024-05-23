<?php

namespace App\Offer\Mapper;

use App\Offer\Dto\OfferCategoryListItemDto;
use App\Offer\Entity\OfferCategory;

class OfferCategoryListItemDtoMapper {

  public static function map(?OfferCategory $offerCategory): ?OfferCategoryListItemDto {
    if ($offerCategory === null) {
      return null;
    }

    return new OfferCategoryListItemDto($offerCategory->getId(), $offerCategory->getName());
  }
}