<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\Wedding;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<Wedding>
 */
class WeddingRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Wedding::class);
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?Wedding {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              wedding 
            FROM 
              App\Wedding\Entity\Wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              wedding.deleted = false 
              AND weddingUsers.user = :userId')
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult();
  }

  public function findAllByUserId(int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              wedding 
            FROM 
              App\Wedding\Entity\Wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND weddingUsers.user = :userId')
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}