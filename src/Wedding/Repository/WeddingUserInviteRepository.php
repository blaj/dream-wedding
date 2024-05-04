<?php

namespace App\Wedding\Repository;

use App\Common\Repository\AbstractWeddingContextRepository;
use App\Wedding\Entity\WeddingUserInvite;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractWeddingContextRepository<WeddingUserInvite>
 */
class WeddingUserInviteRepository extends AbstractWeddingContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, WeddingUserInvite::class);
  }

  public function existsByUserEmail(string $userEmail): bool {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(weddingUserInvite) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\Wedding\Entity\WeddingUserInvite weddingUserInvite 
            WHERE 
              weddingUserInvite.deleted = false 
              AND UPPER(weddingUserInvite.userEmail) LIKE UPPER(:userEmail)')
        ->setParameter('userEmail', $userEmail, Types::STRING)
        ->getSingleScalarResult() > 0;
  }

  public function findOneByUserEmailAndToken(string $userEmail, string $token): ?WeddingUserInvite {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              weddingUserInvite 
            FROM 
              App\Wedding\Entity\WeddingUserInvite weddingUserInvite 
            WHERE 
              weddingUserInvite.deleted = false 
              AND UPPER(weddingUserInvite.userEmail) LIKE UPPER(:userEmail) 
              AND weddingUserInvite.token = :token')
        ->setParameter('userEmail', $userEmail, Types::STRING)
        ->setParameter('token', $token, Types::STRING)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }
}