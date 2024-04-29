<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\WeddingCostEstimateCalculatedDto;
use App\Wedding\Repository\GuestRepository;
use App\Wedding\Repository\WeddingCostEstimateRepository;
use Money\Money;

class WeddingCostEstimateCalculationService {

  public function __construct(
      private readonly WeddingCostEstimateRepository $weddingCostEstimateRepository,
      private readonly GuestRepository $guestRepository) {}

  public function calculate(int $weddingId, int $userId): WeddingCostEstimateCalculatedDto {
    $estimateCost =
        Money::PLN(
            $this->weddingCostEstimateRepository->findEstimateCostByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $realCost =
        Money::PLN(
            $this->weddingCostEstimateRepository->findRealCostByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $guests = $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId);
    $weddingCostEstimatesDependsOnGuests =
        $this->weddingCostEstimateRepository->findAllDependsOnGuestsByWeddingIdAndUserId(
            $weddingId,
            $userId);

    foreach ($guests as $guest) {
      foreach ($weddingCostEstimatesDependsOnGuests as $weddingCostEstimatesDependsOnGuest) {
        $estimateCost =
            $estimateCost->add(
                $weddingCostEstimatesDependsOnGuest->getEstimate()->multiply(
                    $guest->getPayment() / 100)->multiply(
                    $weddingCostEstimatesDependsOnGuest->getQuantity()));

        $realCost =
            $realCost->add(
                $weddingCostEstimatesDependsOnGuest->getReal()
                    ->multiply($guest->getPayment() / 100)
                    ->multiply($weddingCostEstimatesDependsOnGuest->getQuantity()));
      }
    }

    $toPay = $estimateCost->subtract($realCost)->absolute();

    return new WeddingCostEstimateCalculatedDto($estimateCost, $realCost, $toPay);
  }
}