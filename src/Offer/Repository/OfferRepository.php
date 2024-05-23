<?php

namespace App\Offer\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Offer\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<Offer>
 */
class OfferRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Offer::class);
  }

  /**
   * @return array<Offer>
   */
  public function findAllOrderByCreatedAtAscLimitByLimit(int $limit): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              offer 
            FROM 
              App\Offer\Entity\Offer offer 
            WHERE 
              offer.deleted = false 
            ORDER BY
              RANDOM()')
        ->setMaxResults($limit)
        ->getResult();
  }
}