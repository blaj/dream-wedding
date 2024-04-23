<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\Guest;
use App\Wedding\Repository\GuestRepository;
use Doctrine\ORM\EntityNotFoundException;

class GuestFetchService {

  public function __construct(private readonly GuestRepository $guestRepository) {}

  public function fetchGuest(int $id, int $userId): Guest {
    return $this->guestRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Guest not found');
  }
}