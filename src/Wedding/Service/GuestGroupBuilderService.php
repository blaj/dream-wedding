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
    $guestsGroupsBuildDto = [];

    $invitedAmount = 0;
    $confirmedAmount = 0;
    $accommodationAmount = 0;
    $transportAmount = 0;

    $guests = $this->guestRepository->findAllByWeddingIdAndUserId($weddingId, $userId);

    foreach ($guests as $guest) {
      foreach ($guest->getGroups() as $group) {
        if (!array_key_exists($group->getId(), $guestsGroupsBuildDto)) {
          $guestsGroupsBuildDto[$group->getId()] =
              (new GuestGroupBuildRowDto())->setGuestGroupListItemDto(
                  new GuestGroupListItemDto($group->getId(), $group->getName()));
        }

        if ($guest->isInvited()) {
          $invitedAmount++;
        }

        if ($guest->isConfirmed()) {
          $confirmedAmount++;
        }

        if ($guest->isAccommodation()) {
          $accommodationAmount++;
        }

        if ($guest->isTransport()) {
          $transportAmount++;
        }

        $guestListItemDto = GuestListItemDtoMapper::map($guest);

        if ($guestListItemDto === null) {
          continue;
        }

        $guestsGroupsBuildDto[$group->getId()]->addGuestListItemDto($guestListItemDto);
      }
    }

    $guestsCount = count($guests);

    $invitedPercentage =
        $invitedAmount > 0 && $guestsCount > 0
            ? (int) round($invitedAmount / $guestsCount * 100)
            : 0;
    $confirmedPercentage =
        $confirmedAmount > 0 && $guestsCount > 0
            ? (int) round($confirmedAmount / $guestsCount * 100)
            : 0;

    return new GuestGroupBuildDto(
        $guestsGroupsBuildDto,
        $guestsCount,
        $invitedAmount,
        $confirmedAmount,
        $accommodationAmount,
        $transportAmount,
        $invitedPercentage,
        $confirmedPercentage);
  }
}