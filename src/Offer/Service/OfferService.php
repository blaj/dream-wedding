<?php

namespace App\Offer\Service;

use App\Common\Pagination\Dto\PaginatedList;
use App\Common\Pagination\Service\PaginationService;
use App\FileStorage\Service\HeadingImageService;
use App\Offer\Dto\OfferCreateRequest;
use App\Offer\Dto\OfferDetailsDto;
use App\Offer\Dto\OfferListItemDto;
use App\Offer\Dto\OfferPaginatedListCriteria;
use App\Offer\Dto\OfferPaginatedListFilter;
use App\Offer\Dto\OfferUpdateRequest;
use App\Offer\Entity\Offer;
use App\Offer\Entity\OfferCategory;
use App\Offer\Mapper\OfferDetailsDtoMapper;
use App\Offer\Mapper\OfferListItemDtoMapper;
use App\Offer\Mapper\OfferUpdateRequestMapper;
use App\Offer\Repository\OfferRepository;
use Doctrine\ORM\Query;

class OfferService {

  private static int $latestListLimit = 12;

  public function __construct(
      private readonly OfferRepository $offerRepository,
      private readonly OfferFetchService $offerFetchService,
      private readonly OfferCategoryFetchService $offerCategoryFetchService,
      private readonly HeadingImageService $headingImageService,
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

  public function getUpdateRequest(int $id): ?OfferUpdateRequest {
    return OfferUpdateRequestMapper::map($this->offerRepository->findOneById($id));
  }

  public function create(OfferCreateRequest $offerCreateRequest): void {
    $categories =
        array_map(fn (int $id) => $this->offerCategoryFetchService->fetchOfferCategory($id),
            $offerCreateRequest->getCategories());

    $offer = (new Offer())
        ->setTitle($offerCreateRequest->getTitle())
        ->setContent($offerCreateRequest->getContent())
        ->setShortContent($offerCreateRequest->getShortContent());

    array_walk(
        $categories,
        fn (OfferCategory $offerCategory) => $offer->addCategory($offerCategory));

    $headingImage =
        $this->headingImageService->addAndGetHeadingImage($offerCreateRequest->getHeadingImage());

    if ($headingImage !== null) {
      $offer->setHeadingImage($headingImage->localFileResource);
    }

    $this->offerRepository->save($offer);
  }

  public function update(int $id, OfferUpdateRequest $offerUpdateRequest): void {
    $offer = $this->offerFetchService->fetchOffer($id);

    $addedCategories =
        array_map(
            fn (int $categoryId) => $this->offerCategoryFetchService->fetchOfferCategory(
                $categoryId),
            array_filter(
                $offerUpdateRequest->getCategories(),
                fn (int $categoryId) => !in_array(
                    $categoryId,
                    array_map(fn (OfferCategory $offerCategory) => $offerCategory->getId(),
                        $offer->getCategories()->toArray()),
                    true)));
    $removedCategories =
        array_filter(
            $offer->getCategories()->toArray(),
            fn (OfferCategory $offerCategory) => !in_array(
                $offerCategory->getId(),
                $offerUpdateRequest->getCategories(),
                true));

    $offer
        ->setTitle($offerUpdateRequest->getTitle())
        ->setContent($offerUpdateRequest->getContent())
        ->setShortContent($offerUpdateRequest->getShortContent());

    array_walk(
        $addedCategories,
        fn (OfferCategory $offerCategory) => $offer->addCategory($offerCategory));
    array_walk(
        $removedCategories,
        fn (OfferCategory $offerCategory) => $offer->removeCategory($offerCategory));

    $headingImage =
        $this->headingImageService->addAndGetHeadingImage($offerUpdateRequest->getHeadingImage());

    if ($headingImage !== null) {
      $offer->setHeadingImage($headingImage->localFileResource);
    }

    $this->offerRepository->save($offer);
  }

  public function delete(int $id): void {
    $this->offerRepository->softDeleteById($this->offerFetchService->fetchOffer($id)->getId());
  }
}