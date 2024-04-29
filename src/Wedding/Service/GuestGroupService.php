<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestGroupCreateRequest;
use App\Wedding\Dto\GuestGroupDetailsDto;
use App\Wedding\Dto\GuestGroupListItemDto;
use App\Wedding\Dto\GuestGroupUpdateRequest;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\GuestGroup;
use App\Wedding\Mapper\GuestGroupDetailsDtoMapper;
use App\Wedding\Mapper\GuestGroupListItemDtoMapper;
use App\Wedding\Mapper\GuestGroupUpdateRequestMapper;
use App\Wedding\Repository\GuestGroupRepository;

class GuestGroupService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly GuestGroupFetchService $guestGroupFetchService,
      private readonly GuestFetchService $guestFetchService,
      private readonly GuestGroupRepository $guestGroupRepository) {}

  /**
   * @return array<GuestGroupListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (GuestGroup $guestGroup) => GuestGroupListItemDtoMapper::map($guestGroup),
            $this->guestGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?GuestGroupListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?GuestGroupDetailsDto {
    return GuestGroupDetailsDtoMapper::map(
        $this->guestGroupRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?GuestGroupUpdateRequest {
    return GuestGroupUpdateRequestMapper::map(
        $this->guestGroupRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      GuestGroupCreateRequest $guestGroupCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);
    $guests =
        array_map(fn (int $guestId) => $this->guestFetchService->fetchGuest($guestId, $userId),
            $guestGroupCreateRequest->getGuests());

    $guestGroup = (new GuestGroup())
        ->setName($guestGroupCreateRequest->getName())
        ->setDescription($guestGroupCreateRequest->getDescription())
        ->setWedding($wedding);

    array_walk($guests, fn (Guest $guest) => $guestGroup->addGuest($guest));

    $this->guestGroupRepository->save($guestGroup);
  }

  public function update(
      int $id,
      GuestGroupUpdateRequest $guestGroupUpdateRequest,
      int $userId): void {
    $guestGroup = $this->guestGroupFetchService->fetchGuestGroup($id, $userId);
    $addedGuests =
        array_map(fn (int $guestId) => $this->guestFetchService->fetchGuest($guestId, $userId),
            array_filter(
                $guestGroupUpdateRequest->getGuests(),
                fn (int $guestId) => !in_array(
                    $guestId,
                    array_map(fn (Guest $guest) => $guest->getId(),
                        $guestGroup->getGuests()->toArray()),
                    true)));
    $removedGuests =
        array_filter(
            $guestGroup->getGuests()->toArray(),
            fn (Guest $guest) => !in_array(
                $guest->getId(),
                $guestGroupUpdateRequest->getGuests(),
                true));

    $guestGroup
        ->setName($guestGroupUpdateRequest->getName())
        ->setDescription($guestGroupUpdateRequest->getDescription());

    array_walk($addedGuests, fn (Guest $guest) => $guestGroup->addGuest($guest));
    array_walk($removedGuests, fn (Guest $guest) => $guestGroup->removeGuest($guest));
    
    $this->guestGroupRepository->save($guestGroup);
  }

  public function delete(int $id, int $userId): void {
    $this->guestGroupRepository->softDeleteById(
        $this->guestGroupFetchService->fetchGuestGroup($id, $userId)->getId());
  }
}