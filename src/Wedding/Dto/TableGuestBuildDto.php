<?php

namespace App\Wedding\Dto;

class TableGuestBuildDto {

  /**
   * @param array<TableGuestBuildRowDto> $tablesGuestsBuildRowDto
   */
  public function __construct(
      public array $tablesGuestsBuildRowDto,
      public int $numberOfSeats,
      public int $numberOfSeatsPercentage) {}
}