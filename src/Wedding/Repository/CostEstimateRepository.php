<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\CostEstimate;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<CostEstimate>
 */
class CostEstimateRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, CostEstimate::class);
  }

  /**
   * @return array<CostEstimate>
   */
  public function findAllByWeddingIdAndUserIdAndGroupIsNull(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              costEstimate 
            FROM 
              App\Wedding\Entity\CostEstimate costEstimate 
              INNER JOIN costEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              costEstimate.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND costEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId 
              AND costEstimate.group IS NULL')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }

  /**
   * @return array<CostEstimate>
   */
  public function findAllDependsOnGuestsByWeddingIdAndUserId(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              costEstimate 
            FROM 
              App\Wedding\Entity\CostEstimate costEstimate 
              INNER JOIN costEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              costEstimate.deleted = false 
              AND costEstimate.dependsOnGuests = true 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND costEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }

  public function countCostByWeddingIdAndUserIdExcludeDependsOnGuests(
      int $weddingId,
      int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              SUM(costEstimate.cost * costEstimate.quantity) as _cost
            FROM 
              App\Wedding\Entity\CostEstimate costEstimate 
              INNER JOIN costEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              costEstimate.deleted = false 
              AND costEstimate.dependsOnGuests = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND costEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countAdvancePaymentByWeddingIdAndUserIdExcludeDependsOnGuests(
      int $weddingId,
      int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              SUM(costEstimate.advancePayment * costEstimate.quantity) as _advancePayment
            FROM 
              App\Wedding\Entity\CostEstimate costEstimate 
              INNER JOIN costEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              costEstimate.deleted = false 
              AND costEstimate.dependsOnGuests = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND costEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countPaidByWeddingIdAndUserIdExcludeDependsOnGuests(
      int $weddingId,
      int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              SUM(costEstimate.paid * costEstimate.quantity) as _paid
            FROM 
              App\Wedding\Entity\CostEstimate costEstimate 
              INNER JOIN costEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              costEstimate.deleted = false 
              AND costEstimate.dependsOnGuests = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND costEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }
}