<?php

namespace App\Wedding\Repository;

use App\Common\Dto\FullCalendarQueryDto;
use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\Task;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<Task>
 */
class TaskRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Task::class);
  }

  public function countByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(task) AS _count
            FROM 
              App\Wedding\Entity\Task task 
              INNER JOIN task.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              task.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND task.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countCompletedByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(task) AS _count
            FROM 
              App\Wedding\Entity\Task task 
              INNER JOIN task.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              task.deleted = false 
              AND task.completed = true 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND task.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function countExpiredByWeddingIdAndUserId(int $weddingId, int $userId): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(task) AS _count
            FROM 
              App\Wedding\Entity\Task task 
              INNER JOIN task.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              task.deleted = false 
              AND (TO_CHAR(task.onDate, \'YYYY-MM-dd\') < :date) 
              AND task.completed = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND task.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->setParameter('date', new DateTimeImmutable(), Types::DATE_IMMUTABLE)
        ->getSingleScalarResult();
  }

  /**
   * @return array<Task>
   */
  public function findAllByWeddingIdAndUserIdAndGroupIsNull(int $weddingId, int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              task 
            FROM 
              App\Wedding\Entity\Task task 
              INNER JOIN task.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              task.deleted = false 
              AND task.group IS NULL 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND task.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }

  /**
   * @return array<Task>
   */
  public function findAllByWeddingIdAndQueryAndUserId(
      int $weddingId,
      FullCalendarQueryDto $fullCalendarQueryDto,
      int $userId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              task 
            FROM 
              App\Wedding\Entity\Task task 
              INNER JOIN task.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              task.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND task.wedding = :weddingId 
              AND weddingUsers.user = :userId 
              AND task.onDate >= :start 
              AND task.onDate <= :end')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->setParameter('start', $fullCalendarQueryDto->start, Types::DATE_IMMUTABLE)
        ->setParameter('end', $fullCalendarQueryDto->end, Types::DATE_IMMUTABLE)
        ->getResult();
  }
}