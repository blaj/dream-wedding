<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<Table>
 */
class TableRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Table::class);
  }

  public function findSumNumberOfSeatsByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              SUM(table.numberOfSeats) AS _numberOfSeats 
            FROM 
              App\Wedding\Entity\Table table 
              INNER JOIN table.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              table.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND table.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?Table {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              table 
            FROM 
              App\Wedding\Entity\Table table 
              INNER JOIN table.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              table.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND table.id = :id 
              AND weddingUsers.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<Table>
   */
  public function findAllByWeddingIdAndUserId(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              table 
            FROM 
              App\Wedding\Entity\Table table 
              INNER JOIN table.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              table.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND table.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}