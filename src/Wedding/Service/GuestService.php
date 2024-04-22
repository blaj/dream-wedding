<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestListItemDto;
use App\Wedding\Entity\Guest;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Repository\GuestRepository;

class GuestService {

  public function __construct(private readonly GuestRepository $guestRepository) {}

  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Guest $guest) => GuestListItemDtoMapper::map($guest),
            $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?GuestListItemDto $dto) => $dto !== null);
  }
}