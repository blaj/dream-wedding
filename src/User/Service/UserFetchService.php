<?php

namespace App\User\Service;

use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

class UserFetchService {

  public function __construct(private readonly UserRepository $userRepository) {}

  public function fetchUser(int $userId): User {
    return $this->userRepository->findOneById($userId)
        ??
        throw new EntityNotFoundException('User not found');
  }
}