<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\CostEstimateCreateRequest;
use App\Wedding\Dto\CostEstimateDetailsDto;
use App\Wedding\Dto\CostEstimateListItemDto;
use App\Wedding\Dto\CostEstimateUpdateRequest;
use App\Wedding\Entity\CostEstimate;
use App\Wedding\Mapper\CostEstimateDetailsDtoMapper;
use App\Wedding\Mapper\CostEstimateListItemDtoMapper;
use App\Wedding\Mapper\CostEstimateUpdateRequestMapper;
use App\Wedding\Repository\CostEstimateRepository;

class CostEstimateService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly CostEstimateFetchService $costEstimateFetchService,
      private readonly CostEstimateGroupFetchService $costEstimateGroupFetchService,
      private readonly CostEstimateRepository $costEstimateRepository) {}

  /**
   * @return array<CostEstimateListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(
            fn (
                CostEstimate $weddingCostEstimate) => CostEstimateListItemDtoMapper::map(
                $weddingCostEstimate),
            $this->costEstimateRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?CostEstimateListItemDto $dto) => $dto !== null);
  }

  /**
   * @return array<CostEstimateListItemDto>
   */
  public function getUngroupedList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(
            fn (
                CostEstimate $weddingCostEstimate) => CostEstimateListItemDtoMapper::map(
                $weddingCostEstimate),
            $this->costEstimateRepository->findAllByWeddingIdAndUserIdAndGroupIsNull(
                $weddingId,
                $userId)),
        fn (?CostEstimateListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?CostEstimateDetailsDto {
    return CostEstimateDetailsDtoMapper::map(
        $this->costEstimateRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?CostEstimateUpdateRequest {
    return CostEstimateUpdateRequestMapper::map(
        $this->costEstimateRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      CostEstimateCreateRequest $costEstimateCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);
    $group =
        $costEstimateCreateRequest->getGroup() !== null
            ? $this->costEstimateGroupFetchService->fetchCostEstimateGroup(
            $costEstimateCreateRequest->getGroup(),
            $userId)
            : null;

    $costEstimate = (new CostEstimate())
        ->setWedding($wedding)
        ->setName($costEstimateCreateRequest->getName())
        ->setDescription($costEstimateCreateRequest->getDescription())
        ->setCost($costEstimateCreateRequest->getCost())
        ->setAdvancePayment($costEstimateCreateRequest->getAdvancePayment())
        ->setCurrency($costEstimateCreateRequest->getAdvancePayment()->getCurrency())
        ->setQuantity($costEstimateCreateRequest->getQuantity())
        ->setUnitType($costEstimateCreateRequest->getUnitType())
        ->setDependsOnGuests($costEstimateCreateRequest->isDependsOnGuests())
        ->setGroup($group)
        ->setOrderNo($costEstimateCreateRequest->getOrderNo())
        ->setPaid($costEstimateCreateRequest->getPaid());

    $this->costEstimateRepository->save($costEstimate);
  }

  public function update(
      int $id,
      CostEstimateUpdateRequest $costEstimateUpdateRequest,
      int $userId): void {
    $costEstimate =
        $this->costEstimateFetchService->fetchCostEstimate($id, $userId);
    $group =
        $costEstimateUpdateRequest->getGroup() !== null
            ? $this->costEstimateGroupFetchService->fetchCostEstimateGroup(
            $costEstimateUpdateRequest->getGroup(),
            $userId)
            : null;

    $costEstimate
        ->setName($costEstimateUpdateRequest->getName())
        ->setDescription($costEstimateUpdateRequest->getDescription())
        ->setCost($costEstimateUpdateRequest->getCost())
        ->setAdvancePayment($costEstimateUpdateRequest->getAdvancePayment())
        ->setQuantity($costEstimateUpdateRequest->getQuantity())
        ->setUnitType($costEstimateUpdateRequest->getUnitType())
        ->setDependsOnGuests($costEstimateUpdateRequest->isDependsOnGuests())
        ->setGroup($group)
        ->setOrderNo($costEstimateUpdateRequest->getOrderNo())
        ->setPaid($costEstimateUpdateRequest->getPaid());

    $this->costEstimateRepository->save($costEstimate);
  }

  public function updateGroup(int $id, ?int $groupId, int $userId): void {
    $costEstimate = $this->costEstimateFetchService->fetchCostEstimate($id, $userId);
    $group =
        $groupId !== null
            ? $this->costEstimateGroupFetchService->fetchCostEstimateGroup($groupId, $userId)
            : null;

    $costEstimate->setGroup($group);

    $this->costEstimateRepository->save($costEstimate);
  }

  public function updateOrderNo(int $id, int $orderNo, int $userId): void {
    $costEstimate = $this->costEstimateFetchService->fetchCostEstimate($id, $userId);

    $costEstimate->setOrderNo($orderNo);

    $this->costEstimateRepository->save($costEstimate);
  }

  public function delete(int $id, int $userId): void {
    $this->costEstimateRepository->softDeleteById(
        $this->costEstimateFetchService->fetchCostEstimate($id, $userId)->getId());
  }
}