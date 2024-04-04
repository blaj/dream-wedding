<?php

namespace App\Common\Doctrine\Listener;

use App\Common\Entity\AuditingEntity;
use App\Security\Trait\GetUserFromSecurityTrait;
use App\User\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: AuditingEntity::class)]
class AuditingEntityPreUpdateListener {

  use GetUserFromSecurityTrait;

  public function __construct(
      private readonly Security $security,
      private readonly UserRepository $userRepository) {}

  public function preUpdate(AuditingEntity $auditingEntity, PreUpdateEventArgs $event): void {
    $auditingEntity
        ->setUpdatedAt(new DateTimeImmutable())
        ->setUpdatedBy($this->getUser($this->security, $this->userRepository));
  }
}