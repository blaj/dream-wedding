<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\CostEstimateGroupCreateRequest;
use App\Wedding\Dto\CostEstimateGroupDetailsDto;
use App\Wedding\Dto\CostEstimateGroupListItemDto;
use App\Wedding\Dto\CostEstimateGroupUpdateRequest;
use App\Wedding\Entity\CostEstimateGroup;
use App\Wedding\Entity\CostEstimate;
use App\Wedding\Mapper\CostEstimateGroupDetailsDtoMapper;
use App\Wedding\Mapper\CostEstimateGroupListItemDtoMapper;
use App\Wedding\Mapper\CostEstimateGroupUpdateRequestMapper;
use App\Wedding\Repository\CostEstimateGroupRepository;

class CostEstimateGroupService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly CostEstimateGroupFetchService $costEstimateGroupFetchService,
      private readonly CostEstimateFetchService $weddingCostEstimateFetchService,
      private readonly CostEstimateGroupRepository $costEstimateGroupRepository) {}

  /**
   * @return array<CostEstimateGroupListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(
            fn (CostEstimateGroup $costEstimateGroup) => CostEstimateGroupListItemDtoMapper::map(
                $costEstimateGroup),
            $this->costEstimateGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?CostEstimateGroupListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?CostEstimateGroupDetailsDto {
    return CostEstimateGroupDetailsDtoMapper::map(
        $this->costEstimateGroupRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?CostEstimateGroupUpdateRequest {
    return CostEstimateGroupUpdateRequestMapper::map(
        $this->costEstimateGroupRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      CostEstimateGroupCreateRequest $costEstimateGroupCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);
    $costEstimates =
        array_map(
            fn (
                int $costEstimateId) => $this->weddingCostEstimateFetchService->fetchCostEstimate(
                $costEstimateId,
                $userId),
            $costEstimateGroupCreateRequest->getCostEstimates());

    $costEstimateGroup = (new CostEstimateGroup())
        ->setName($costEstimateGroupCreateRequest->getName())
        ->setDescription($costEstimateGroupCreateRequest->getDescription())
        ->setWedding($wedding);

    array_walk(
        $costEstimates,
        fn (CostEstimate $costEstimate) => $costEstimateGroup->addCostEstimate(
            $costEstimate));

    $this->costEstimateGroupRepository->save($costEstimateGroup);
  }

  public function update(
      int $id,
      CostEstimateGroupUpdateRequest $costEstimateGroupUpdateRequest,
      int $userId): void {
    $costEstimateGroup = $this->costEstimateGroupFetchService->fetchCostEstimateGroup($id, $userId);
    $addedCostEstimates =
        array_map(
            fn (
                int $costEstimateId) => $this->weddingCostEstimateFetchService->fetchCostEstimate(
                $costEstimateId,
                $userId),
            array_filter(
                $costEstimateGroupUpdateRequest->getCostEstimates(),
                fn (int $costEstimateId) => !in_array(
                    $costEstimateId,
                    array_map(
                        fn (
                            CostEstimate $weddingCostEstimate) => $weddingCostEstimate->getId(),
                        $costEstimateGroup->getCostEstimates()->toArray()),
                    true)));
    $removedCostEstimates =
        array_filter(
            $costEstimateGroup->getCostEstimates()->toArray(),
            fn (CostEstimate $costEstimate) => !in_array(
                $costEstimate->getId(),
                $costEstimateGroupUpdateRequest->getCostEstimates(),
                true));

    $costEstimateGroup
        ->setName($costEstimateGroupUpdateRequest->getName())
        ->setDescription($costEstimateGroupUpdateRequest->getDescription());

    array_walk(
        $addedCostEstimates,
        fn (CostEstimate $costEstimate) => $costEstimateGroup->addCostEstimate(
            $costEstimate));
    array_walk(
        $removedCostEstimates,
        fn (CostEstimate $costEstimate) => $costEstimateGroup->removeCostEstimate(
            $costEstimate));

    $this->costEstimateGroupRepository->save($costEstimateGroup);
  }

  public function delete(int $id, int $userId): void {
    $this->costEstimateGroupRepository->softDeleteById(
        $this->costEstimateGroupFetchService->fetchCostEstimateGroup($id, $userId)->getId());
  }
}