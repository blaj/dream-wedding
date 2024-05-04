<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\WeddingCostEstimate;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<WeddingCostEstimate>
 */
class WeddingCostEstimateRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, WeddingCostEstimate::class);
  }

  /**
   * @return array<WeddingCostEstimate>
   */
  public function findAllDependsOnGuestsByWeddingIdAndUserId(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingCostEstimate 
            FROM 
              App\Wedding\Entity\WeddingCostEstimate weddingCostEstimate 
              INNER JOIN weddingCostEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              weddingCostEstimate.deleted = false 
              AND weddingCostEstimate.dependsOnGuests = true 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND weddingCostEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }

  public function findRealCostByWeddingIdAndUserIdExcludeDependsOnGuests(
      int $weddingId,
      int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              SUM(weddingCostEstimate.real * weddingCostEstimate.quantity) as _realCost
            FROM 
              App\Wedding\Entity\WeddingCostEstimate weddingCostEstimate 
              INNER JOIN weddingCostEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              weddingCostEstimate.deleted = false 
              AND weddingCostEstimate.dependsOnGuests = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND weddingCostEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function findEstimateCostByWeddingIdAndUserIdExcludeDependsOnGuests(
      int $weddingId,
      int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              SUM(weddingCostEstimate.estimate * weddingCostEstimate.quantity) as _estimateCost
            FROM 
              App\Wedding\Entity\WeddingCostEstimate weddingCostEstimate 
              INNER JOIN weddingCostEstimate.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              weddingCostEstimate.deleted = false 
              AND weddingCostEstimate.dependsOnGuests = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND weddingCostEstimate.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }
}