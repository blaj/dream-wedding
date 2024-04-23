<?php

namespace App\Wedding\Service;

use App\User\Service\UserFetchService;
use App\Wedding\Dto\WeddingCreateRequest;
use App\Wedding\Dto\WeddingDetailsDto;
use App\Wedding\Dto\WeddingListItemDto;
use App\Wedding\Dto\WeddingUpdateRequest;
use App\Wedding\Entity\Enum\RoleType;
use App\Wedding\Entity\Wedding;
use App\Wedding\Entity\WeddingUser;
use App\Wedding\Mapper\WeddingDetailsDtoMapper;
use App\Wedding\Mapper\WeddingListItemDtoMapper;
use App\Wedding\Mapper\WeddingUpdateRequestMapper;
use App\Wedding\Repository\WeddingRepository;
use App\Wedding\Repository\WeddingUserRepository;
use Doctrine\ORM\EntityManagerInterface;

class WeddingService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly UserFetchService $userFetchService,
      private readonly WeddingRepository $weddingRepository,
      private readonly WeddingUserRepository $weddingUserRepository,
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

  public function getUpdateRequest(int $id, int $userId): ?WeddingUpdateRequest {
    return WeddingUpdateRequestMapper::map(
        $this->weddingRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(WeddingCreateRequest $weddingCreateRequest, int $userId): void {
    $wedding =
        (new Wedding())
            ->setName($weddingCreateRequest->getName())
            ->setOnDate($weddingCreateRequest->getOnDate());

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
        ->setOnDate($weddingUpdateRequest->getOnDate());
    $this->weddingRepository->save($wedding);
  }

  public function delete(int $id, int $userId): void {
    // TODO: delete all relations
    $this->weddingRepository->softDeleteById(
        $this->weddingFetchService->fetchWedding($id, $userId)->getId());
  }
}