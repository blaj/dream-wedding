<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingUserInviteListItemDto;
use App\Wedding\Entity\WeddingUserInvite;
use DateTimeImmutable;

class WeddingUserInviteListItemDtoMapper {

  private static int $canResendMinutesDelay = 30;

  public static function map(?WeddingUserInvite $weddingUserInvite): ?WeddingUserInviteListItemDto {
    if ($weddingUserInvite === null) {
      return null;
    }

    return new WeddingUserInviteListItemDto(
        $weddingUserInvite->getId(),
        $weddingUserInvite->getUserEmail(),
        $weddingUserInvite->getCreatedAt(),
        $weddingUserInvite->getRole(),
        self::canResend($weddingUserInvite->getCreatedAt(), $weddingUserInvite->getUpdatedAt()));
  }

  private static function canResend(
      DateTimeImmutable $createdAt,
      ?DateTimeImmutable $updatedAt): bool {
    $lastSendDate = $updatedAt !== null ? $updatedAt : $createdAt;
    $now = new DateTimeImmutable();

    return $now->diff($lastSendDate)->i >= self::$canResendMinutesDelay;
  }
}