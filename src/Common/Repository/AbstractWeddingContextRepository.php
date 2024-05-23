<?php

namespace App\Common\Repository;

use App\Common\Entity\AuditingEntity;
use App\Common\Entity\WeddingContextInterface;
use App\Wedding\Entity\Task;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;

/**
 * @template T of AuditingEntity&WeddingContextInterface
 * @extends AbstractAuditingEntityRepository<T>
 */
abstract class AbstractWeddingContextRepository extends AbstractAuditingEntityRepository {

  /**
   * @return T
   */
  public function findOneByIdAndUserId(int $id, int $userId): ?object {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
              INNER JOIN entity.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              entity.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND entity.id = :id 
              AND weddingUsers.user = :userId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<T>
   */
  public function findAllByWeddingIdAndUserId(int $weddingId, int $userId): array {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
              INNER JOIN entity.wedding wedding 
              INNER JOIN wedding.weddingUsers weddingUsers 
            WHERE 
              entity.deleted = false 
              AND wedding.deleted = false 
              AND weddingUsers.deleted = false 
              AND entity.wedding = :weddingId 
              AND weddingUsers.user = :userId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('userId', $userId, Types::INTEGER)
        ->getResult();
  }

  public function softDeleteByWeddingId(int $weddingId): void {
    $this->getEntityManager()
        ->createQuery(
            '
          UPDATE 
            ' . $this->getClassName() . ' entity 
          SET 
            entity.deleted = true, 
            entity.deletedAt = :deletedAt 
          WHERE 
            entity.wedding = :weddingId ')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->setParameter('deletedAt', new DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
        ->execute();
  }
}