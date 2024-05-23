<?php

namespace App\Offer\Service;

use App\Offer\Dto\OfferListItemDto;
use App\Offer\Entity\Offer;
use App\Offer\Mapper\OfferListItemDtoMapper;
use App\Offer\Repository\OfferRepository;

class OfferService {

  private static int $latestListLimit = 12;

  public function __construct(private readonly OfferRepository $offerRepository) {}

  /**
   * @return array<OfferListItemDto>
   */
  public function getLatestList(): array {
    return array_filter(
        array_map(
            fn (Offer $offer) => OfferListItemDtoMapper::map($offer),
            $this->offerRepository->findAllOrderByCreatedAtAscLimitByLimit(self::$latestListLimit)),
        fn (?OfferListItemDto $dto) => $dto !== null);
  }
}