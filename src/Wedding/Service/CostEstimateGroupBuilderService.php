<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\CostEstimateGroupBuildDto;
use App\Wedding\Mapper\CostEstimateGroupListItemDtoMapper;
use App\Wedding\Mapper\CostEstimateListItemDtoMapper;
use App\Wedding\Repository\CostEstimateGroupRepository;

class CostEstimateGroupBuilderService {

  public function __construct(
      private readonly CostEstimateGroupRepository $costEstimateGroupRepository) {}

  /**
   * @return array<CostEstimateGroupBuildDto>
   */
  public function build(int $weddingId, int $userId): array {
    $costEstimateGroupsBuildDto = [];

    $costEstimateGroups =
        $this->costEstimateGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($costEstimateGroups as $costEstimateGroup) {
      if (!array_key_exists($costEstimateGroup->getId(), $costEstimateGroupsBuildDto)) {
        $costEstimateGroupListItemDto = CostEstimateGroupListItemDtoMapper::map($costEstimateGroup);

        if ($costEstimateGroupListItemDto === null) {
          continue;
        }

        $costEstimateGroupsBuildDto[$costEstimateGroup->getId()] =
            (new CostEstimateGroupBuildDto())->setCostEstimateGroupListItemDto(
                $costEstimateGroupListItemDto);
      }

      foreach ($costEstimateGroup->getCostEstimates() as $costEstimate) {
        $costEstimateListItemDto = CostEstimateListItemDtoMapper::map($costEstimate);

        if ($costEstimateListItemDto === null) {
          continue;
        }

        $costEstimateGroupsBuildDto[$costEstimateGroup->getId()]->addCostEstimateListItemDto(
            $costEstimateListItemDto);
      }
    }

    return $costEstimateGroupsBuildDto;
  }
}