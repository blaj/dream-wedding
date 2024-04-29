<?php

namespace App\Wedding\Dto;

readonly class GuestGroupBuildDto {

  /**
   * @param array<GuestGroupBuildRowDto> $guestsGroupsBuildRowDto
   */
  public function __construct(
      public array $guestsGroupsBuildRowDto,
      public int $guestsAmount,
      public int $invitedAmount,
      public int $confirmedAmount,
      public int $accommodationAmount,
      public int $transportAmount,
      public int $invitedPercentage,
      public int $confirmedPercentage) {}
}