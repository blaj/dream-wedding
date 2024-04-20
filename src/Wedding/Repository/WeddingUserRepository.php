<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\WeddingUser;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<WeddingUser>
 */
class WeddingUserRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, WeddingUser::class);
  }

  /**
   * @return array<WeddingUser>
   */
  public function findAllByUserId(int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUser 
            FROM 
              App\Wedding\Entity\WeddingUser weddingUser 
            WHERE 
              weddingUser.deleted = false 
              AND weddingUser.user = :userId')
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getArrayResult();
  }
}