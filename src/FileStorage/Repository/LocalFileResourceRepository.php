<?php

namespace App\FileStorage\Repository;

use App\FileStorage\Entity\LocalFileResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LocalFileResource>
 */
class LocalFileResourceRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, LocalFileResource::class);
  }

  public function save(LocalFileResource $entity, bool $flush = true): void {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }
}