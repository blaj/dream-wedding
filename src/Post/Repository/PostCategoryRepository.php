<?php

namespace App\Post\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Post\Entity\PostCategory;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<PostCategory>
 */
class PostCategoryRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, PostCategory::class);
  }
}