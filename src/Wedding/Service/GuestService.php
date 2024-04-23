<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestCreateRequest;
use App\Wedding\Dto\GuestDetailsDto;
use App\Wedding\Dto\GuestListItemDto;
use App\Wedding\Dto\GuestUpdateRequest;
use App\Wedding\Entity\Guest;
use App\Wedding\Mapper\GuestDetailsDtoMapper;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Mapper\GuestUpdateRequestMapper;
use App\Wedding\Repository\GuestRepository;

class GuestService {

  public function __construct(
      private readonly GuestFetchService $guestFetchService,
      private readonly WeddingFetchService $weddingFetchService,
      private readonly GuestRepository $guestRepository) {}

  /**
   * @return array<GuestListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Guest $guest) => GuestListItemDtoMapper::map($guest),
            $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?GuestListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?GuestDetailsDto {
    return GuestDetailsDtoMapper::map($this->guestRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?GuestUpdateRequest {
    return GuestUpdateRequestMapper::map(
        $this->guestRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      GuestCreateRequest $guestCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);

    $guest = (new Guest())
        ->setFirstName($guestCreateRequest->getFirstName())
        ->setLastName($guestCreateRequest->getLastName())
        ->setWedding($wedding);

    $this->guestRepository->save($guest);
  }

  public function update(int $id, GuestUpdateRequest $guestUpdateRequest, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);
    $guest
        ->setFirstName($guestUpdateRequest->getFirstName())
        ->setLastName($guestUpdateRequest->getLastName());
    $this->guestRepository->save($guest);
  }

  public function delete(int $id, int $userId): void {
    // TODO: delete all relations
    $this->guestRepository->softDeleteById(
        $this->guestFetchService->fetchGuest($id, $userId)->getId());
  }
}