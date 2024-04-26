<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\WeddingUser;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<WeddingUser>
 */
class WeddingUserRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, WeddingUser::class);
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?WeddingUser {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUser 
            FROM 
              App\Wedding\Entity\WeddingUser weddingUser 
            WHERE 
              weddingUser.deleted = false 
              AND weddingUser.id = :id 
              AND weddingUser.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<WeddingUser>
   */
  public function findAllByWeddingId(int $weddingId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUser 
            FROM 
              App\Wedding\Entity\WeddingUser weddingUser 
            WHERE 
              weddingUser.deleted = false 
              AND weddingUser.wedding = :weddingId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->getResult();
  }
}