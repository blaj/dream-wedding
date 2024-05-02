<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Wedding\Entity\Task;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<Task>
 */
class TaskRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Task::class);
  }

  public function findOneByIdAndUserId(int $id, int $userId): ?Task {
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
              AND task.id = :id 
              AND weddingUsers.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<Task>
   */
  public function findAllByWeddingIdAndUserId(int $weddingId, int $userId): array {
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
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }
}