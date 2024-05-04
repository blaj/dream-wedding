<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\WeddingUser;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<WeddingUser>
 */
class WeddingUserRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, WeddingUser::class);
  }

  /**
   * @return array<WeddingUser>
   */
  public function findAllByWeddingId(int $weddingId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUser 
            FROM 
              App\Wedding\Entity\WeddingUser weddingUser 
            WHERE 
              weddingUser.deleted = false 
              AND weddingUser.wedding = :weddingId')
        ->setParameter('weddingId', $weddingId, Types::INTEGER)
        ->getResult();
  }
}