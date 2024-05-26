<?php

namespace App\Offer\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Common\Utils\DoctrineUtils;
use App\Offer\Dto\OfferPaginatedListFilter;
use App\Offer\Entity\Offer;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
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

  public function countAll(): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(offer) AS _count
            FROM 
              App\Offer\Entity\Offer offer 
            WHERE 
              offer.deleted = false ')
        ->getSingleScalarResult();
  }

  public function getPaginationQuery(): Query {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              offer 
            FROM 
              App\Offer\Entity\Offer offer 
              INNER JOIN offer.categories categories 
            WHERE 
              offer.deleted = false 
              AND categories.deleted = false');
  }

  public function getCountPaginationQuery(): Query {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(offer) as _count 
            FROM 
              App\Offer\Entity\Offer offer 
              INNER JOIN offer.categories categories 
            WHERE 
              offer.deleted = false 
              AND categories.deleted = false');
  }

  public function appendPaginationFilter(
      Query $query,
      OfferPaginatedListFilter $offerPaginatedListFilter): void {
    if ($offerPaginatedListFilter->getSearchBy() !== null
        && strlen($offerPaginatedListFilter->getSearchBy()) > 0) {
      DoctrineUtils::appendToDQL(
          $query,
          ' AND offer.title LIKE :searchBy ',
          'searchBy',
          '%' . $offerPaginatedListFilter->getSearchBy() . '%',
          Types::STRING);
    }

    if (count($offerPaginatedListFilter->getCategories()) > 0) {
      DoctrineUtils::appendToDQL(
          $query,
          ' AND categories.id IN (:categories) ',
          'categories',
          $offerPaginatedListFilter->getCategories(),
          null);
    }
  }
}