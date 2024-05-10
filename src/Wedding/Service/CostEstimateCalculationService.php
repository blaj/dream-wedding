<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\CostEstimateCalculatedDto;
use App\Wedding\Repository\GuestRepository;
use App\Wedding\Repository\CostEstimateRepository;
use Money\Money;

class CostEstimateCalculationService {

  public function __construct(
      private readonly CostEstimateRepository $costEstimateRepository,
      private readonly GuestRepository $guestRepository) {}

  public function calculate(int $weddingId, int $userId): CostEstimateCalculatedDto {
    $estimateCost =
        Money::PLN(
            $this->costEstimateRepository->findEstimateCostByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $realCost =
        Money::PLN(
            $this->costEstimateRepository->findRealCostByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $guests = $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId);
    $costEstimatesDependsOnGuests =
        $this->costEstimateRepository->findAllDependsOnGuestsByWeddingIdAndUserId(
            $weddingId,
            $userId);

    foreach ($guests as $guest) {
      foreach ($costEstimatesDependsOnGuests as $costEstimatesDependsOnGuest) {
        $estimateCost =
            $estimateCost->add(
                $costEstimatesDependsOnGuest->getEstimate()->multiply(
                    $guest->getPayment() / 100)->multiply(
                    $costEstimatesDependsOnGuest->getQuantity()));

        $realCost =
            $realCost->add(
                $costEstimatesDependsOnGuest->getReal()
                    ->multiply($guest->getPayment() / 100)
                    ->multiply($costEstimatesDependsOnGuest->getQuantity()));
      }
    }

    $toPay = $estimateCost->subtract($realCost)->absolute();

    return new CostEstimateCalculatedDto($estimateCost, $realCost, $toPay);
  }
}