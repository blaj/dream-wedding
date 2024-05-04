<?php

namespace App\Wedding\Repository;

use App\Common\Dto\FullCalendarQueryDto;
use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\Task;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<Task>
 */
class TaskRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Task::class);
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