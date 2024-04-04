<?php

namespace App\Security\Trait;

use App\Security\Dto\UserData;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

trait GetUserFromSecurityTrait {

  public function getUser(Security $security, UserRepository $userRepository): ?User {
    $user = $security->getUser();

    if (!$user instanceof UserData) {
      return null;
    }

    return $userRepository->findOneById($user->getUserId());
  }
}