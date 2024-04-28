<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\GuestGroup;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<GuestGroup>
 */
class GuestGroupRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, GuestGroup::class);
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?GuestGroup {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              guestGroup 
            FROM 
              App\Wedding\Entity\GuestGroup guestGroup 
              INNER JOIN guestGroup.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guestGroup.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guestGroup.id = :id 
              AND weddingUsers.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<GuestGroup>
   */
  public function findAllByWeddingIdAndUserId(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              guestGroup 
            FROM 
              App\Wedding\Entity\GuestGroup guestGroup 
              INNER JOIN guestGroup.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guestGroup.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guestGroup.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}