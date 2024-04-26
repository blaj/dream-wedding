<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\WeddingUser;
use App\Wedding\Repository\WeddingUserRepository;
use Doctrine\ORM\EntityNotFoundException;

class WeddingUserFetchService {

  public function __construct(private readonly WeddingUserRepository $weddingUserRepository) {}

  public function fetchWeddingUser(int $id, int $userId): WeddingUser {
    return $this->weddingUserRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Wedding user not found');
  }
}