<?php

namespace App\Common\Doctrine\Listener;

use App\Common\Entity\AuditingEntity;
use App\Security\Trait\GetUserFromSecurityTrait;
use App\User\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: AuditingEntity::class)]
class AuditingEntityPrePersistListener {

  use GetUserFromSecurityTrait;

  public function __construct(
      private readonly Security $security,
      private readonly UserRepository $userRepository) {}

  public function prePersist(AuditingEntity $auditingEntity, PrePersistEventArgs $event): void {
    $auditingEntity
        ->setCreatedAt(new DateTimeImmutable())
        ->setCreatedBy($this->getUser($this->security, $this->userRepository));
  }
}