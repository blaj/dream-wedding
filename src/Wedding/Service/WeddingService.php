<?php

namespace App\Wedding\Service;

use App\Wedding\Repository\WeddingRepository;

class WeddingService {

  public function __construct(private readonly WeddingRepository $weddingRepository) {}

  public function getList(int $userId): array {
    return [];
  }
}