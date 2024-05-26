<?php

namespace App\Offer\Mapper;

use App\Offer\Dto\OfferDetailsDto;
use App\Offer\Entity\Offer;
use App\Offer\Entity\OfferCategory;

class OfferDetailsDtoMapper {

  public static function map(?Offer $offer): ?OfferDetailsDto {
    if ($offer === null) {
      return null;
    }

    return new OfferDetailsDto(
        $offer->getId(),
        $offer->getTitle(),
        $offer->getContent(),
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