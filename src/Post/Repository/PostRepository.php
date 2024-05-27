<?php

namespace App\Post\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Post\Entity\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<Post>
 */
class PostRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Post::class);
  }

  public function countAll(): int {
    return (int) $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              COUNT(post) as _count 
            FROM 
              App\Post\Entity\Post post 
            WHERE 
              post.deleted = false')
        ->getSingleScalarResult();
  }

  /**
   * @return array<Post>
   */
  public function findAllLimitByLimitAndOffsetByOffset(int $limit, int $offset): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              post 
            FROM 
              App\Post\Entity\Post post 
            WHERE 
              post.deleted = false')
        ->setMaxResults($limit)
        ->setFirstResult($offset)
        ->getResult();
  }

  /**
   * @return array<Post>
   */
  public function findAllOrderByCreatedAtAscLimitByLimit(int $limit): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              post 
            FROM 
              App\Post\Entity\Post post 
            WHERE 
              post.deleted = false 
            ORDER BY
              post.createdAt ASC')
        ->setMaxResults($limit)
        ->getResult();
  }
}