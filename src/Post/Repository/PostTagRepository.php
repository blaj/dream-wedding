<?php

namespace App\Post\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\Post\Entity\PostTag;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<PostTag>
 */
class PostTagRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, PostTag::class);
  }
}