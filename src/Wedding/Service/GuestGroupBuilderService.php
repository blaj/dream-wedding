<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\GuestGroupBuildDto;
use App\Wedding\Dto\GuestGroupBuildRowDto;
use App\Wedding\Dto\GuestGroupListItemDto;
use App\Wedding\Mapper\GuestListItemDtoMapper;
use App\Wedding\Repository\GuestRepository;

class GuestGroupBuilderService {

  public function __construct(private readonly GuestRepository $guestRepository) {}

  public function build(int $weddingId, int $userId): GuestGroupBuildDto {
    $guestsGroupsBuildRowDto = [];

    $guests = $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($guests as $guest) {
      foreach ($guest->getGroups() as $group) {
        if (!array_key_exists($group->getId(), $guestsGroupsBuildRowDto)) {
          $guestsGroupsBuildRowDto[$group->getId()] =
              (new GuestGroupBuildRowDto())
                  ->setGuestGroupListItemDto(
                      new GuestGroupListItemDto($group->getId(), $group->getName()));
        }

        $guestListItemDto = GuestListItemDtoMapper::map($guest);

        if ($guestListItemDto === null) {
          continue;
        }

        $guestsGroupsBuildRowDto[$group->getId()]->addGuestListItemDto($guestListItemDto);
      }
    }

    $guestsCount = count($guests);

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