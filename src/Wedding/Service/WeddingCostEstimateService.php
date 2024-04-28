<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\WeddingCostEstimateCreateRequest;
use App\Wedding\Dto\WeddingCostEstimateDetailsDto;
use App\Wedding\Dto\WeddingCostEstimateListItemDto;
use App\Wedding\Dto\WeddingCostEstimateUpdateRequest;
use App\Wedding\Entity\WeddingCostEstimate;
use App\Wedding\Mapper\WeddingCostEstimateDetailsDtoMapper;
use App\Wedding\Mapper\WeddingCostEstimateListItemDtoMapper;
use App\Wedding\Mapper\WeddingCostEstimateUpdateRequestMapper;
use App\Wedding\Repository\WeddingCostEstimateRepository;

class WeddingCostEstimateService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly WeddingCostEstimateFetchService $weddingCostEstimateFetchService,
      private readonly WeddingCostEstimateRepository $weddingCostEstimateRepository) {}

  /**
   * @return array<WeddingCostEstimateListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(
            fn (
                WeddingCostEstimate $weddingCostEstimate) => WeddingCostEstimateListItemDtoMapper::map(
                $weddingCostEstimate),
            $this->weddingCostEstimateRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?WeddingCostEstimateListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?WeddingCostEstimateDetailsDto {
    return WeddingCostEstimateDetailsDtoMapper::map(
        $this->weddingCostEstimateRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?WeddingCostEstimateUpdateRequest {
    return WeddingCostEstimateUpdateRequestMapper::map(
        $this->weddingCostEstimateRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      WeddingCostEstimateCreateRequest $weddingCostEstimateCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);

    $weddingCostEstimate = (new WeddingCostEstimate())
        ->setWedding($wedding)
        ->setName($weddingCostEstimateCreateRequest->getName())
        ->setDescription($weddingCostEstimateCreateRequest->getDescription())
        ->setEstimate($weddingCostEstimateCreateRequest->getEstimate())
        ->setReal($weddingCostEstimateCreateRequest->getReal())
        ->setCurrency($weddingCostEstimateCreateRequest->getReal()->getCurrency());

    $this->weddingCostEstimateRepository->save($weddingCostEstimate);
  }

  public function update(
      int $id,
      WeddingCostEstimateUpdateRequest $weddingCostEstimateUpdateRequest,
      int $userId): void {
    $weddingCostEstimate =
        $this->weddingCostEstimateFetchService->fetchWeddingCostEstimate($id, $userId);
    $weddingCostEstimate
        ->setName($weddingCostEstimateUpdateRequest->getName())
        ->setDescription($weddingCostEstimateUpdateRequest->getDescription())
        ->setEstimate($weddingCostEstimateUpdateRequest->getEstimate())
        ->setReal($weddingCostEstimateUpdateRequest->getReal());
    $this->weddingCostEstimateRepository->save($weddingCostEstimate);
  }

  public function delete(int $id, int $userId): void {
    $this->weddingCostEstimateRepository->softDeleteById(
        $this->weddingCostEstimateFetchService->fetchWeddingCostEstimate($id, $userId)->getId());
  }
}