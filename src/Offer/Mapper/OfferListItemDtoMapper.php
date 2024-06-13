<?php

namespace App\Offer\Mapper;

use App\Offer\Dto\OfferListItemDto;
use App\Offer\Entity\Offer;
use App\Offer\Entity\OfferCategory;

class OfferListItemDtoMapper {

  public static function map(?Offer $offer): ?OfferListItemDto {
    if ($offer === null) {
      return null;
    }

    return new OfferListItemDto(
        $offer->getId(),
        $offer->getTitle(),
        $offer->getContent(),
        $offer->getShortContent(),
        $offer->getHeadingImage()?->getPath(),
        self::categoryNames($offer->getCategories()->toArray()));
  }

  /**
   * @param array<OfferCategory> $categories
   *
   * @return array<string>
   */
  private static function categoryNames(array $categories): array {
    return array_map(fn (OfferCategory $category) => $category->getName(), $categories);
  }
}