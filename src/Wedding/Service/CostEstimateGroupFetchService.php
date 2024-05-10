<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\CostEstimateGroup;
use App\Wedding\Repository\CostEstimateGroupRepository;
use Doctrine\ORM\EntityNotFoundException;

class CostEstimateGroupFetchService {

  public function __construct(
      private readonly CostEstimateGroupRepository $costEstimateGroupRepository) {}

  public function fetchCostEstimateGroup(int $id, int $userId): CostEstimateGroup {
    return $this->costEstimateGroupRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Cost estimate group not found');
  }
}