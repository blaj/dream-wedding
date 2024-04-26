<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\WeddingUserInvite;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<WeddingUserInvite>
 */
class WeddingUserInviteRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, WeddingUserInvite::class);
  }

  public function existsByUserEmail(string $userEmail): bool {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(weddingUserInvite) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\Wedding\Entity\WeddingUserInvite weddingUserInvite 
            WHERE 
              weddingUserInvite.deleted = false 
              AND UPPER(weddingUserInvite.userEmail) LIKE UPPER(:userEmail)')
        ->setParameter('userEmail', $userEmail, Types::STRING)
        ->getSingleScalarResult() > 0;
  }

  public function findOneByUserEmailAndToken(string $userEmail, string $token): ?WeddingUserInvite {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUserInvite 
            FROM 
              App\Wedding\Entity\WeddingUserInvite weddingUserInvite 
            WHERE 
              weddingUserInvite.deleted = false 
              AND UPPER(weddingUserInvite.userEmail) LIKE UPPER(:userEmail) 
              AND weddingUserInvite.token = :token')
        ->setParameter('userEmail', $userEmail, Types::STRING)
        ->setParameter('token', $token, Types::STRING)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?WeddingUserInvite {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUserInvite 
            FROM 
              App\Wedding\Entity\WeddingUserInvite weddingUserInvite 
              INNER JOIN weddingUserInvite.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              weddingUserInvite.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND weddingUserInvite.id = :id 
              AND weddingUsers.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<WeddingUserInvite>
   */
  public function findAllByWeddingIdAndUserId(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUserInvite 
            FROM 
              App\Wedding\Entity\WeddingUserInvite weddingUserInvite 
              INNER JOIN weddingUserInvite.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              weddingUserInvite.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND weddingUserInvite.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}