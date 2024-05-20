<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestGroupBuildDto;
use App\Wedding\Dto\GuestGroupBuildRowDto;
use App\Wedding\Dto\GuestGroupListItemDto;
use App\Wedding\Dto\GuestListFilterRequest;
use App\Wedding\Mapper\GuestGroupListItemDtoMapper;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Repository\GuestGroupRepository;
use App\Wedding\Repository\GuestRepository;

class GuestGroupBuilderService {

  public function __construct(
      private readonly GuestRepository $guestRepository,
      private readonly GuestGroupRepository $guestGroupRepository) {}

  public function build(
      int $weddingId,
      GuestListFilterRequest $guestListFilterRequest,
      int $userId): GuestGroupBuildDto {
    $guestsGroupsBuildRowDto = [];

    $guests = $this->guestRepository->findAllByWeddingIdAndFilterAndUserIdAndGroupIsNotNull($weddingId, $guestListFilterRequest, $userId);
    $guestGroups = $this->guestGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($guestGroups as $guestGroup) {
      $groupId = $guestGroup->getId();

      if (!array_key_exists($groupId, $guestsGroupsBuildRowDto)) {
        $guestGroupListItemDto = GuestGroupListItemDtoMapper::map($guestGroup);

        if ($guestGroupListItemDto === null) {
          continue;
        }

        $guestsGroupsBuildRowDto[$groupId] =
            (new GuestGroupBuildRowDto())->setGuestGroupListItemDto($guestGroupListItemDto);
      }
    }

    foreach ($guests as $guest) {
      $groupId = $guest->getGroup()?->getId();

      if ($groupId === null) {
        continue;
      }

      if (!array_key_exists($groupId, $guestsGroupsBuildRowDto)) {
        $guestGroupListItemDto = GuestGroupListItemDtoMapper::map($guest->getGroup());

        if ($guestGroupListItemDto === null) {
          continue;
        }

        $guestsGroupsBuildRowDto[$groupId] =
            (new GuestGroupBuildRowDto())->setGuestGroupListItemDto($guestGroupListItemDto);
      }

      $guestListItemDto = GuestListItemDtoMapper::map($guest);

      if ($guestListItemDto === null) {
        continue;
      }

      $guestsGroupsBuildRowDto[$groupId]->addGuestListItemDto($guestListItemDto);
    }

    $guestsCount = $this->guestRepository->countByWeddingIdAndUserId($weddingId, $userId);
    $invitedAmount = $this->guestRepository->countInvitedByWeddingIdAndUserId($weddingId, $userId);
    $confirmedAmount =
        $this->guestRepository->countConfirmedByWeddingIdAndUserId($weddingId, $userId);
    $accommodationAmount =
        $this->guestRepository->countAccommodationByWeddingIdAndUserId($weddingId, $userId);
    $transportAmount =
        $this->guestRepository->countTransportByWeddingIdAndUserId($weddingId, $userId);

    $invitedPercentage =
        $invitedAmount > 0 && $guestsCount > 0
            ? (int) round($invitedAmount / $guestsCount * 100)
            : 0;
    $confirmedPercentage =
        $confirmedAmount > 0 && $guestsCount > 0
            ? (int) round($confirmedAmount / $guestsCount * 100)
            : 0;

    return new GuestGroupBuildDto(
        $guestsGroupsBuildRowDto,
        $guestsCount,
        $invitedAmount,
        $confirmedAmount,
        $accommodationAmount,
        $transportAmount,
        $invitedPercentage,
        $confirmedPercentage);
  }
}