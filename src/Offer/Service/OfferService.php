<?php

namespace App\Offer\Service;

use App\Common\Pagination\Dto\PaginatedList;
use App\Common\Pagination\Service\PaginationService;
use App\Offer\Dto\OfferDetailsDto;
use App\Offer\Dto\OfferListItemDto;
use App\Offer\Dto\OfferPaginatedListCriteria;
use App\Offer\Dto\OfferPaginatedListFilter;
use App\Offer\Entity\Offer;
use App\Offer\Mapper\OfferDetailsDtoMapper;
use App\Offer\Mapper\OfferListItemDtoMapper;
use App\Offer\Repository\OfferRepository;
use Doctrine\ORM\Query;

class OfferService {

  private static int $latestListLimit = 12;

  public function __construct(
      private readonly OfferRepository $offerRepository,
      private readonly PaginationService $paginationService) {}

  public function getCount(): int {
    return $this->offerRepository->countAll();
  }

  /**
   * @return PaginatedList<OfferListItemDto>
   */
  public function getPaginatedList(
      OfferPaginatedListCriteria $offerPaginatedListCriteria): PaginatedList {
    return $this->paginationService->paginate(
        OfferListItemDto::class,
        OfferPaginatedListFilter::class,
        $this->offerRepository->getPaginationQuery(),
        $this->offerRepository->getCountPaginationQuery(),
        $offerPaginatedListCriteria,
        fn (
            Query $query,
            OfferPaginatedListFilter $offerPaginatedListFilter) => $this->offerRepository->appendPaginationFilter(
            $query,
            $offerPaginatedListFilter),
        fn (Offer $offer) => OfferListItemDtoMapper::map($offer));
  }

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

  public function getOne(int $id): ?OfferDetailsDto {
    return OfferDetailsDtoMapper::map($this->offerRepository->findOneById($id));
  }
}