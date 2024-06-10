<?php

namespace App\FileStorage\Repository;

use App\Common\Repository\AbstractAuditingEntityRepository;
use App\FileStorage\Entity\LocalFileResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractAuditingEntityRepository<LocalFileResource>
 */
class LocalFileResourceRepository extends AbstractAuditingEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, LocalFileResource::class);
  }

  public function findOneByPathAndCreatedById(string $path, int $createdById): ?LocalFileResource {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              localFileResource 
            FROM 
              App\FileStorage\Entity\LocalFileResource localFileResource 
            WHERE 
              localFileResource.deleted = false 
              AND UPPER(localFileResource.path) LIKE UPPER(:path) 
              AND localFileResource.createdBy = :createdById')
        ->setParameter('path', $path, Types::STRING)
        ->setParameter('createdById', $createdById, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }
}