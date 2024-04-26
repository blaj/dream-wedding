<?php

namespace App\Wedding\Mapper;

use App\User\Entity\User;
use App\Wedding\Dto\WeddingUserListItemDto;
use App\Wedding\Entity\WeddingUser;

class WeddingUserListItemDtoMapper {

  public static function map(?WeddingUser $weddingUser): ?WeddingUserListItemDto {
    if ($weddingUser === null) {
      return null;
    }
    
    return new WeddingUserListItemDto(
        $weddingUser->getId(),
        $weddingUser->getUser()->getUsername(),
        $weddingUser->getUser()->getEmail(),
        $weddingUser->getRole(),
        self::canDelete($weddingUser->getUser(), $weddingUser->getCreatedBy()));
  }

  private static function canDelete(User $user, ?User $createdBy): bool {
    if ($createdBy !== null) {
      return $user !== $createdBy;
    }

    return false;
  }
}