<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\Guest;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<Guest>
 */
class GuestRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Guest::class);
  }

  public function countByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(guest) 
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
        ->getSingleScalarResult();
  }

  public function countInvitedByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(guest) 
            FROM 
              App\Wedding\Entity\Guest guest 
              INNER JOIN guest.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guest.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guest.wedding = :weddingId 
              AND weddingUsers.user = :userId 
              AND guest.invited = true')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countConfirmedByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(guest) 
            FROM 
              App\Wedding\Entity\Guest guest 
              INNER JOIN guest.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guest.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guest.wedding = :weddingId 
              AND weddingUsers.user = :userId 
              AND guest.confirmed = true')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countAccommodationByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(guest) 
            FROM 
              App\Wedding\Entity\Guest guest 
              INNER JOIN guest.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guest.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guest.wedding = :weddingId 
              AND weddingUsers.user = :userId 
              AND guest.accommodation = true')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countTransportByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(guest) 
            FROM 
              App\Wedding\Entity\Guest guest 
              INNER JOIN guest.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              guest.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND guest.wedding = :weddingId 
              AND weddingUsers.user = :userId 
              AND guest.transport = true')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  /**
   * @return array<Guest>
   */
  public function findAllByWeddingIdAndUserIdAndGroupIsNull(int $weddingId, int $userId): array {
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
              AND weddingUsers.user = :userId 
              AND guest.group IS NULL')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}