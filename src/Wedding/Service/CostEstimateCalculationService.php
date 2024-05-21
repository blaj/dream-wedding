<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\CostEstimateCalculatedDto;
use App\Wedding\Repository\GuestRepository;
use App\Wedding\Repository\CostEstimateRepository;
use App\Wedding\Repository\WeddingRepository;
use Money\Money;

class CostEstimateCalculationService {

  public function __construct(
      private readonly CostEstimateRepository $costEstimateRepository,
      private readonly GuestRepository $guestRepository,
      private readonly WeddingRepository $weddingRepository) {}

  public function calculate(int $weddingId, int $userId): CostEstimateCalculatedDto {
    $advancePayment =
        Money::PLN(
            $this->costEstimateRepository->countAdvancePaymentByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $cost =
        Money::PLN(
            $this->costEstimateRepository->countCostByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $paid =
        Money::PLN(
            $this->costEstimateRepository->countPaidByWeddingIdAndUserIdExcludeDependsOnGuests(
                $weddingId,
                $userId));

    $guests = $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId);
    $costEstimatesDependsOnGuests =
        $this->costEstimateRepository->findAllDependsOnGuestsByWeddingIdAndUserId(
            $weddingId,
            $userId);

    foreach ($guests as $guest) {
      foreach ($costEstimatesDependsOnGuests as $costEstimatesDependsOnGuest) {
        $advancePayment =
            $advancePayment->add(
                $costEstimatesDependsOnGuest->getCost()->multiply(
                    $guest->getPayment() / 100)->multiply(
                    $costEstimatesDependsOnGuest->getQuantity()));

        $cost =
            $cost->add(
                $costEstimatesDependsOnGuest->getAdvancePayment()
                    ->multiply($guest->getPayment() / 100)
                    ->multiply($costEstimatesDependsOnGuest->getQuantity()));

        $paid =
            $paid->add(
                $costEstimatesDependsOnGuest->getPaid()
                    ->multiply($guest->getPayment() / 100)
                    ->multiply($costEstimatesDependsOnGuest->getQuantity()));
      }
    }

    $toPay = $paid->add($advancePayment);
    $toPay = $toPay->subtract($cost)->absolute();

    $wedding = $this->weddingRepository->findOneByIdAndUserId($weddingId, $userId);

    $paidWithAdvancePaymentAmount = (int) $paid->add($advancePayment)->getAmount();
    $weddingBudget = (int) $wedding?->getBudget()->getAmount();

    $budgetPercentage =
        $weddingBudget > 0 && $paidWithAdvancePaymentAmount > 0
            ? (int) round($paidWithAdvancePaymentAmount / $weddingBudget * 100)
            : 0;

    return new CostEstimateCalculatedDto($cost, $advancePayment, $paid, $toPay, $budgetPercentage);
  }
}