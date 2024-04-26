<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\WeddingUserDetailsDto;
use App\Wedding\Dto\WeddingUserListItemDto;
use App\Wedding\Dto\WeddingUserUpdateRequest;
use App\Wedding\Entity\WeddingUser;
use App\Wedding\Mapper\WeddingUserDetailsDtoMapper;
use App\Wedding\Mapper\WeddingUserListItemDtoMapper;
use App\Wedding\Mapper\WeddingUserUpdateRequestMapper;
use App\Wedding\Repository\WeddingUserRepository;

class WeddingUserService {

  public function __construct(
      private readonly WeddingUserFetchService $weddingUserFetchService,
      private readonly WeddingUserRepository $weddingUserRepository) {}

  /**
   * @return array<WeddingUserListItemDto>
   */
  public function getList(int $weddingId): array {
    return array_filter(
        array_map(fn (WeddingUser $weddingUser) => WeddingUserListItemDtoMapper::map($weddingUser),
            $this->weddingUserRepository->findAllByWeddingId($weddingId)),
        fn (?WeddingUserListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?WeddingUserDetailsDto {
    return WeddingUserDetailsDtoMapper::map(
        $this->weddingUserRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?WeddingUserUpdateRequest {
    return WeddingUserUpdateRequestMapper::map(
        $this->weddingUserRepository->findOneByIdAndUserId($id, $userId));
  }

  public function update(
      int $id,
      WeddingUserUpdateRequest $weddingUserUpdateRequest,
      int $userId): void {
    $weddingUser = $this->weddingUserFetchService->fetchWeddingUser($id, $userId);
    $weddingUser->setRole($weddingUserUpdateRequest->getRole());

    $this->weddingUserRepository->save($weddingUser);
  }

  public function delete(int $id, int $userId): void {
    $this->weddingUserRepository->softDeleteById(
        $this->weddingUserFetchService->fetchWeddingUser($id, $userId)->getId());
  }
}