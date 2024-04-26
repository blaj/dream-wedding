<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\WeddingCostEstimate;
use App\Wedding\Repository\WeddingCostEstimateRepository;
use Doctrine\ORM\EntityNotFoundException;

class WeddingCostEstimateFetchService {

  public function __construct(
      private readonly WeddingCostEstimateRepository $weddingCostEstimateRepository) {}

  public function fetchWeddingCostEstimate(int $id, int $userId): WeddingCostEstimate {
    return $this->weddingCostEstimateRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Wedding cost estimate not found');
  }
}