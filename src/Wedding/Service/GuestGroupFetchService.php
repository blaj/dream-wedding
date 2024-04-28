<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\GuestGroup;
use App\Wedding\Repository\GuestGroupRepository;
use Doctrine\ORM\EntityNotFoundException;

class GuestGroupFetchService {

  public function __construct(private readonly GuestGroupRepository $guestGroupRepository) {}

  public function fetchGuestGroup(int $id, int $userId): GuestGroup {
    return $this->guestGroupRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Guest group not found');
  }
}