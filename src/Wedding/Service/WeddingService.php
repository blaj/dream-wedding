<?php

namespace App\Wedding\Service;

use App\Common\Entity\Address;
use App\User\Service\UserFetchService;
use App\Wedding\Dto\WeddingCreateRequest;
use App\Wedding\Dto\WeddingDetailsDto;
use App\Wedding\Dto\WeddingListItemDto;
use App\Wedding\Dto\WeddingNearestDto;
use App\Wedding\Dto\WeddingUpdateRequest;
use App\Wedding\Entity\Enum\RoleType;
use App\Wedding\Entity\Wedding;
use App\Wedding\Entity\WeddingUser;
use App\Wedding\Mapper\WeddingDetailsDtoMapper;
use App\Wedding\Mapper\WeddingListItemDtoMapper;
use App\Wedding\Mapper\WeddingNearestDtoMapper;
use App\Wedding\Mapper\WeddingUpdateRequestMapper;
use App\Wedding\Repository\CostEstimateGroupRepository;
use App\Wedding\Repository\CostEstimateRepository;
use App\Wedding\Repository\GuestGroupRepository;
use App\Wedding\Repository\GuestRepository;
use App\Wedding\Repository\TableRepository;
use App\Wedding\Repository\TaskGroupRepository;
use App\Wedding\Repository\TaskRepository;
use App\Wedding\Repository\WeddingRepository;
use App\Wedding\Repository\WeddingUserInviteRepository;
use App\Wedding\Repository\WeddingUserRepository;
use Doctrine\ORM\EntityManagerInterface;

class WeddingService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly UserFetchService $userFetchService,
      private readonly WeddingRepository $weddingRepository,
      private readonly WeddingUserRepository $weddingUserRepository,
      private readonly CostEstimateGroupRepository $costEstimateGroupRepository,
      private readonly CostEstimateRepository $costEstimateRepository,
      private readonly GuestGroupRepository $guestGroupRepository,
      private readonly GuestRepository $guestRepository,
      private readonly TableRepository $tableRepository,
      private readonly TaskGroupRepository $taskGroupRepository,
      private readonly TaskRepository $taskRepository,
      private readonly WeddingUserInviteRepository $weddingUserInviteRepository,
      private readonly EntityManagerInterface $entityManager) {}

  /**
   * @return array<WeddingListItemDto>
   */
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

  public function getOneNearest(int $userId): ?WeddingNearestDto {
    return WeddingNearestDtoMapper::map($this->weddingRepository->findOneNearestByUserId($userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?WeddingUpdateRequest {
    return WeddingUpdateRequestMapper::map(
        $this->weddingRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(WeddingCreateRequest $weddingCreateRequest, int $userId): void {
    $wedding =
        (new Wedding())
            ->setName($weddingCreateRequest->getName())
            ->setOnDate($weddingCreateRequest->getOnDate())
            ->setBudget($weddingCreateRequest->getBudget())
            ->setWeddingAddress(
                (new Address())
                    ->setCity($weddingCreateRequest->getWeddingAddress()->getCity())
                    ->setStreet($weddingCreateRequest->getWeddingAddress()->getStreet())
                    ->setPostcode($weddingCreateRequest->getWeddingAddress()->getPostcode()))
            ->setPartyAddress(
                (new Address())
                    ->setCity($weddingCreateRequest->getPartyAddress()->getCity())
                    ->setStreet($weddingCreateRequest->getPartyAddress()->getStreet())
                    ->setPostcode($weddingCreateRequest->getPartyAddress()->getPostcode()));

    $weddingUser =
        (new WeddingUser())
            ->setWedding($wedding)
            ->setUser($this->userFetchService->fetchUser($userId))
            ->setRole(RoleType::OWNER);

    $this->entityManager->beginTransaction();
    $this->weddingRepository->save($wedding, false);
    $this->weddingUserRepository->save($weddingUser, false);
    $this->entityManager->flush();
    $this->entityManager->commit();
  }

  public function update(int $id, WeddingUpdateRequest $weddingUpdateRequest, int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($id, $userId);

    $wedding
        ->setName($weddingUpdateRequest->getName())
        ->setOnDate($weddingUpdateRequest->getOnDate())
        ->setBudget($weddingUpdateRequest->getBudget())
        ->setWeddingAddress(
            (new Address())
                ->setCity($weddingUpdateRequest->getWeddingAddress()->getCity())
                ->setStreet($weddingUpdateRequest->getWeddingAddress()->getStreet())
                ->setPostcode($weddingUpdateRequest->getWeddingAddress()->getPostcode()))
        ->setPartyAddress(
            (new Address())
                ->setCity($weddingUpdateRequest->getPartyAddress()->getCity())
                ->setStreet($weddingUpdateRequest->getPartyAddress()->getStreet())
                ->setPostcode($weddingUpdateRequest->getPartyAddress()->getPostcode()));

    $this->weddingRepository->save($wedding);
  }

  public function delete(int $id, int $userId): void {
    $this->entityManager->beginTransaction();

    $weddingId = $this->weddingFetchService->fetchWedding($id, $userId)->getId();

    $this->weddingRepository->softDeleteById($weddingId);
    $this->costEstimateGroupRepository->softDeleteByWeddingId($weddingId);
    $this->costEstimateRepository->softDeleteByWeddingId($weddingId);
    $this->guestGroupRepository->softDeleteByWeddingId($weddingId);
    $this->guestRepository->softDeleteByWeddingId($weddingId);
    $this->tableRepository->softDeleteByWeddingId($weddingId);
    $this->taskGroupRepository->softDeleteByWeddingId($weddingId);
    $this->taskRepository->softDeleteByWeddingId($weddingId);
    $this->weddingUserInviteRepository->softDeleteByWeddingId($weddingId);
    $this->weddingUserRepository->softDeleteByWeddingId($weddingId);

    $this->entityManager->flush();
    $this->entityManager->commit();
  }
}