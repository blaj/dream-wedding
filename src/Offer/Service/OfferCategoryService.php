<?php

namespace App\Offer\Service;

use App\Offer\Dto\OfferCategoryListItemDto;
use App\Offer\Entity\OfferCategory;
use App\Offer\Mapper\OfferCategoryListItemDtoMapper;
use App\Offer\Repository\OfferCategoryRepository;

class OfferCategoryService {

  private static int $randomListLimit = 10;

  public function __construct(private readonly OfferCategoryRepository $offerCategoryRepository) {}

  /**
   * @return array<OfferCategoryListItemDto>
   */
  public function getList(): array {
    return array_filter(
        array_map(
            fn (OfferCategory $offerCategory) => OfferCategoryListItemDtoMapper::map(
                $offerCategory),
            $this->offerCategoryRepository->findAll()),
        fn (?OfferCategoryListItemDto $dto) => $dto !== null);
  }

  /**
   * @return array<OfferCategoryListItemDto>
   */
  public function getRandomList(): array {
    return array_filter(
        array_map(
            fn (OfferCategory $offerCategory) => OfferCategoryListItemDtoMapper::map(
                $offerCategory),
            $this->offerCategoryRepository->findAllRandomOrderAscLimitByLimit(
                self::$randomListLimit)),
        fn (?OfferCategoryListItemDto $dto) => $dto !== null);
  }
}