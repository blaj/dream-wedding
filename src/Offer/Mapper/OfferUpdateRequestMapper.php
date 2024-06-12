<?php

namespace App\Offer\Mapper;

use App\Offer\Dto\OfferUpdateRequest;
use App\Offer\Entity\Offer;
use App\Offer\Entity\OfferCategory;

class OfferUpdateRequestMapper {

  public static function map(?Offer $offer): ?OfferUpdateRequest {
    if ($offer === null) {
      return null;
    }

    return (new OfferUpdateRequest())
        ->setTitle($offer->getTitle())
        ->setContent($offer->getContent())
        ->setHeadingImagePath($offer->getHeadingImage()?->getPath())
        ->setCategories(self::categories($offer->getCategories()->toArray()));
  }

  /**
   * @param array<OfferCategory> $categories
   *
   * @return array<int>
   */
  private static function categories(array $categories): array {
    return array_map(fn (OfferCategory $offerCategory) => $offerCategory->getId(), $categories);
  }
}