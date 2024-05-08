<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestGroupBuildDto;
use App\Wedding\Dto\GuestGroupBuildRowDto;
use App\Wedding\Dto\GuestGroupListItemDto;
use App\Wedding\Mapper\GuestGroupListItemDtoMapper;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Repository\GuestGroupRepository;
use App\Wedding\Repository\GuestRepository;

class GuestGroupBuilderService {

  public function __construct(
      private readonly GuestRepository $guestRepository,
      private readonly GuestGroupRepository $guestGroupRepository) {}

  public function build(int $weddingId, int $userId): GuestGroupBuildDto {
    $guestsGroupsBuildRowDto = [];

    $guestGroups = $this->guestGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($guestGroups as $guestGroup) {
      if (!array_key_exists($guestGroup->getId(), $guestsGroupsBuildRowDto)) {
        $guestGroupListItemDto = GuestGroupListItemDtoMapper::map($guestGroup);

        if ($guestGroupListItemDto === null) {
          continue;
        }

        $guestsGroupsBuildRowDto[$guestGroup->getId()] =
            (new GuestGroupBuildRowDto())->setGuestGroupListItemDto($guestGroupListItemDto);
      }

      foreach ($guestGroup->getGuests() as $guest) {
        $guestListItemDto = GuestListItemDtoMapper::map($guest);

        if ($guestListItemDto === null) {
          continue;
        }

        $guestsGroupsBuildRowDto[$guestGroup->getId()]->addGuestListItemDto($guestListItemDto);
      }
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