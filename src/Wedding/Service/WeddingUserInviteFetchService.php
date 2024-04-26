<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\WeddingUserInvite;
use App\Wedding\Repository\WeddingUserInviteRepository;
use Doctrine\ORM\EntityNotFoundException;

class WeddingUserInviteFetchService {

  public function __construct(
      private readonly WeddingUserInviteRepository $weddingUserInviteRepository) {}

  public function fetchWeddingUserInvite(int $id, int $userId): WeddingUserInvite {
    return $this->weddingUserInviteRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Wedding user invite not found');
  }
}