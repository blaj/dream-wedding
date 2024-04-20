<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\WeddingDetailsDto;
use App\Wedding\Dto\WeddingListItemDto;
use App\Wedding\Entity\Wedding;
use App\Wedding\Mapper\WeddingDetailsDtoMapper;
use App\Wedding\Mapper\WeddingListItemDtoMapper;
use App\Wedding\Repository\WeddingRepository;

class WeddingService {

  public function __construct(private readonly WeddingRepository $weddingRepository) {}

  public function getList(int $userId): array {
    return array_filter(
        array_map(fn (Wedding $wedding) => WeddingListItemDtoMapper::map($wedding),
            $this->weddingRepository->findAllByUserId($userId)),
        fn (?WeddingListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?WeddingDetailsDto {
    return WeddingDetailsDtoMapper::map(
        $this->weddingRepository->findOneByIdAndUserId($id, $userId));
  }
}