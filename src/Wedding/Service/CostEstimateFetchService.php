<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\CostEstimate;
use App\Wedding\Repository\CostEstimateRepository;
use Doctrine\ORM\EntityNotFoundException;

class CostEstimateFetchService {

  public function __construct(
      private readonly CostEstimateRepository $weddingCostEstimateRepository) {}

  public function fetchCostEstimate(int $id, int $userId): CostEstimate {
    return $this->weddingCostEstimateRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Wedding cost estimate not found');
  }
}