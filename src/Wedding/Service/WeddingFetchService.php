<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\Wedding;
use App\Wedding\Repository\WeddingRepository;
use Doctrine\ORM\EntityNotFoundException;

class WeddingFetchService {

  public function __construct(private readonly WeddingRepository $weddingRepository) {}

  public function fetchWedding(int $id, int $userId): Wedding {
    return $this->weddingRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Wedding not found');
  }
}