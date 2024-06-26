<?php

namespace App\Common\Repository;

use App\Common\Entity\AuditingEntity;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;

/**
 * @template T of AuditingEntity
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractAuditingEntityRepository extends ServiceEntityRepository {

  /**
   * @param T $entity
   */
  public function save($entity, bool $flush = true): void {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  /**
   * @return T|null
   */
  public function findOneById(int $id): ?object {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
            WHERE 
              entity.deleted = false 
              AND entity.id = :id ')
        ->setParameter('id', $id, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  /**
   * @return array<int, T>
   */
  public function findAll(): array {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
            WHERE 
              entity.deleted = false ')
        ->getResult();
  }

  public function softDeleteById(int $id): void {
    $this->getEntityManager()
        ->createQuery(
            '
          UPDATE 
            ' . $this->getClassName() . ' entity
          SET 
            entity.deleted = true, 
            entity.deletedAt = :deletedAt 
          WHERE 
            entity.id = :id ')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('deletedAt', new DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
        ->execute();
  }
}