<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestCreateRequest;
use App\Wedding\Dto\GuestDetailsDto;
use App\Wedding\Dto\GuestListItemDto;
use App\Wedding\Dto\GuestUpdateRequest;
use App\Wedding\Entity\Guest;
use App\Wedding\Entity\GuestGroup;
use App\Wedding\Mapper\GuestDetailsDtoMapper;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Mapper\GuestUpdateRequestMapper;
use App\Wedding\Repository\GuestRepository;

class GuestService {

  public function __construct(
      private readonly GuestFetchService $guestFetchService,
      private readonly WeddingFetchService $weddingFetchService,
      private readonly GuestGroupFetchService $guestGroupFetchService,
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
    $groups =
        array_map(fn (int $id) => $this->guestGroupFetchService->fetchGuestGroup($id, $userId),
            $guestCreateRequest->getGroups());

    $guest = (new Guest())
        ->setFirstName($guestCreateRequest->getFirstName())
        ->setLastName($guestCreateRequest->getLastName())
        ->setWedding($wedding)
        ->setType($guestCreateRequest->getType())
        ->setInvited($guestCreateRequest->isInvited())
        ->setConfirmed($guestCreateRequest->isConfirmed())
        ->setAccommodation($guestCreateRequest->isAccommodation())
        ->setTransport($guestCreateRequest->isTransport())
        ->setDietType($guestCreateRequest->getDietType())
        ->setNote($guestCreateRequest->getNote())
        ->setTelephone($guestCreateRequest->getTelephone())
        ->setEmail($guestCreateRequest->getEmail())
        ->setPayment($guestCreateRequest->getPayment());

    array_walk($groups, fn (GuestGroup $guestGroup) => $guest->addGroup($guestGroup));

    $this->guestRepository->save($guest);
  }

  public function update(int $id, GuestUpdateRequest $guestUpdateRequest, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);
    $addedGroups =
        array_map(
            fn (int $groupId) => $this->guestGroupFetchService->fetchGuestGroup($groupId, $userId),
            array_filter(
                $guestUpdateRequest->getGroups(),
                fn (int $groupId) => !in_array(
                    $groupId,
                    array_map(fn (GuestGroup $guestGroup) => $guestGroup->getId(),
                        $guest->getGroups()->toArray()),
                    true)));
    $removedGroups =
        array_filter(
            $guest->getGroups()->toArray(),
            fn (GuestGroup $guestGroup) => !in_array(
                $guestGroup->getId(),
                $guestUpdateRequest->getGroups(),
                true));

    $guest
        ->setFirstName($guestUpdateRequest->getFirstName())
        ->setLastName($guestUpdateRequest->getLastName())
        ->setType($guestUpdateRequest->getType())
        ->setInvited($guestUpdateRequest->isInvited())
        ->setConfirmed($guestUpdateRequest->isConfirmed())
        ->setAccommodation($guestUpdateRequest->isAccommodation())
        ->setTransport($guestUpdateRequest->isTransport())
        ->setDietType($guestUpdateRequest->getDietType())
        ->setNote($guestUpdateRequest->getNote())
        ->setTelephone($guestUpdateRequest->getTelephone())
        ->setEmail($guestUpdateRequest->getEmail())
        ->setPayment($guestUpdateRequest->getPayment());

    array_walk($addedGroups, fn (GuestGroup $guestGroup) => $guest->addGroup($guestGroup));
    array_walk($removedGroups, fn (GuestGroup $guestGroup) => $guest->removeGroup($guestGroup));

    $this->guestRepository->save($guest);
  }

  public function delete(int $id, int $userId): void {
    // TODO: delete all relations
    $this->guestRepository->softDeleteById(
        $this->guestFetchService->fetchGuest($id, $userId)->getId());
  }
}