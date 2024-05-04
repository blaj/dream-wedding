<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\Table;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<Table>
 */
class TableRepository extends AbstractWeddingContextRepository {

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
}