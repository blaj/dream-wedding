<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingNearestDto;
use App\Wedding\Entity\Wedding;
use DateTimeImmutable;

class WeddingNearestDtoMapper {

  public static function map(?Wedding $wedding): ?WeddingNearestDto {
    if ($wedding === null) {
      return null;
    }

    return new WeddingNearestDto(
        $wedding->getId(),
        $wedding->getName(),
        $wedding->getOnDate(),
        self::daysLeft($wedding->getOnDate()));
  }

  private static function daysLeft(DateTimeImmutable $onDate): int {
    $daysLeft = (new DateTimeImmutable('midnight'))->diff($onDate)->days;

    return is_int($daysLeft) && $daysLeft >= 0 ? $daysLeft : 0;
  }
}