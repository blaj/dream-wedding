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
      private readonly TableFetchService $tableFetchService,
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

  /**
   * @return array<GuestListItemDto>
   */
  public function getUngroupedList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Guest $guest) => GuestListItemDtoMapper::map($guest),
            $this->guestRepository->findAllByWeddingIdAndUserIdAndGroupIsNull(
                $weddingId,
                $userId)),
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

    $group =
        $guestCreateRequest->getGroup() !== null ? $this->guestGroupFetchService->fetchGuestGroup(
            $guestCreateRequest->getGroup(),
            $userId) : null;
    $table =
        $guestCreateRequest->getTable() !== null
            ? $this->tableFetchService->fetchTable($guestCreateRequest->getTable(), $userId)
            : null;

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
        ->setPayment($guestCreateRequest->getPayment())
        ->setGroup($group)
        ->setTable($table)
        ->setGroupOrderNo($guestCreateRequest->getOrderNo());

    $this->guestRepository->save($guest);
  }

  public function update(int $id, GuestUpdateRequest $guestUpdateRequest, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);

    $group =
        $guestUpdateRequest->getGroup() !== null ? $this->guestGroupFetchService->fetchGuestGroup(
            $guestUpdateRequest->getGroup(),
            $userId) : null;
    $table =
        $guestUpdateRequest->getTable() !== null
            ? $this->tableFetchService->fetchTable($guestUpdateRequest->getTable(), $userId)
            : null;

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
        ->setPayment($guestUpdateRequest->getPayment())
        ->setGroup($group)
        ->setTable($table)
        ->setGroupOrderNo($guestUpdateRequest->getOrderNo());

    $this->guestRepository->save($guest);
  }

  public function updateGroup(int $id, ?int $groupId, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);
    $group =
        $groupId !== null
            ? $this->guestGroupFetchService->fetchGuestGroup($groupId, $userId)
            : null;

    $guest->setGroup($group);

    $this->guestRepository->save($guest);
  }

  public function updateOrderNo(int $id, int $orderNo, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);

    $guest->setGroupOrderNo($orderNo);

    $this->guestRepository->save($guest);
  }

  public function updateTable(int $id, int $tableId, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);
    $table = $this->tableFetchService->fetchTable($tableId, $userId);

    $guest->setTable($table);

    $this->guestRepository->save($guest);
  }

  public function updateTableOrderNo(int $id, int $orderNo, int $userId): void {
    $guest = $this->guestFetchService->fetchGuest($id, $userId);

    $guest->setTableOrderNo($orderNo);

    $this->guestRepository->save($guest);
  }

  public function delete(int $id, int $userId): void {
    // TODO: delete all relations
    $this->guestRepository->softDeleteById(
        $this->guestFetchService->fetchGuest($id, $userId)->getId());
  }
}