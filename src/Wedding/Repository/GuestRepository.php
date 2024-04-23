<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\Guest;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<Guest>
 */
class GuestRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Guest::class);
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?Guest {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              guest 
            FROM 
              App\Wedding\Entity\Guest guest 
              INNER JOIN guest.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guest.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guest.id = :id 
              AND weddingUsers.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<Guest>
   */
  public function findAllByWeddingIdAndUserId(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              guest 
            FROM 
              App\Wedding\Entity\Guest guest 
              INNER JOIN guest.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guest.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guest.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}