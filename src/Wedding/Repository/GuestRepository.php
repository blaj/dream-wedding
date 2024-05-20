<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Dto\GuestListFilterRequest;
use App\Wedding\Entity\Guest;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
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
  public function findAllByWeddingIdAndFilterAndUserIdAndGroupIsNull(
      int $weddingId,
      GuestListFilterRequest $guestListFilterRequest,
      int $userId): array {
    $query = $this->getEntityManager()
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
        ->setParameter('userId', $userId, Types::INTEGER);

    self::appendFilterToQuery($query, $guestListFilterRequest);

    return $query->getResult();
  }

  /**
   * @return array<Guest>
   */
  public function findAllByWeddingIdAndFilterAndUserIdAndGroupIsNotNull(
      int $weddingId,
      GuestListFilterRequest $guestListFilterRequest,
      int $userId): array {
    $query = $this->getEntityManager()
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
              AND guest.group IS NOT NULL')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER);

    self::appendFilterToQuery($query, $guestListFilterRequest);

    return $query->getResult();
  }

  private static function appendFilterToQuery(
      Query $query,
      GuestListFilterRequest $guestListFilterRequest): void {
    if ($guestListFilterRequest->getFirstName() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND UPPER(guest.firstName) LIKE UPPER(:firstName) ')
          ->setParameter('firstName', $guestListFilterRequest->getFirstName(), Types::STRING);
    }

    if ($guestListFilterRequest->getLastName() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND UPPER(guest.lastName) LIKE UPPER(:lastName) ')
          ->setParameter('lastName', $guestListFilterRequest->getLastName(), Types::STRING);
    }

    if ($guestListFilterRequest->getInvited() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND guest.invited = :invited ')
          ->setParameter('invited', $guestListFilterRequest->getInvited(), Types::BOOLEAN);
    }

    if ($guestListFilterRequest->getConfirmed() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND guest.confirmed = :confirmed ')
          ->setParameter('confirmed', $guestListFilterRequest->getConfirmed(), Types::BOOLEAN);
    }

    if ($guestListFilterRequest->getAccommodation() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND guest.accommodation = :accommodation ')
          ->setParameter('accommodation', $guestListFilterRequest->getAccommodation(), Types::BOOLEAN);
    }

    if ($guestListFilterRequest->getTransport() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND guest.transport = :transport ')
          ->setParameter('transport', $guestListFilterRequest->getTransport(), Types::BOOLEAN);
    }

    if ($guestListFilterRequest->getDietType() !== null) {
      $query
          ->setDQL($query->getDQL() . ' AND UPPER(guest.dietType) LIKE UPPER(:dietType) ')
          ->setParameter('dietType', $guestListFilterRequest->getDietType()->value, Types::STRING);
    }
  }
}