<?php

namespace App\Offer\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Offer\Entity\OfferCategory;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<OfferCategory>
 */
class OfferCategoryRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, OfferCategory::class);
  }

  /**
   * @return array<OfferCategory>
   */
  public function findAllRandomOrderAscLimitByLimit(int $limit): array {
    // TODO: this is not optimal solution for large amount of data, use TABLESAMPLE instead: https://www.2ndquadrant.com/en/blog/tablesample-in-postgresql-9-5-2/
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              offerCategory 
            FROM 
              App\Offer\Entity\OfferCategory offerCategory 
            WHERE 
              offerCategory.deleted = false 
            ORDER BY
              RANDOM()')
        ->setMaxResults($limit)
        ->getResult();
  }
}