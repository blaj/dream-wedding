<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingUserDetailsDto;
use App\Wedding\Entity\WeddingUser;

class WeddingUserDetailsDtoMapper {

  public static function map(?WeddingUser $weddingUser): ?WeddingUserDetailsDto {
    if ($weddingUser === null) {
      return null;
    }

    return new WeddingUserDetailsDto(
        $weddingUser->getId(),
        $weddingUser->getUser()->getUsername(),
        $weddingUser->getUser()->getEmail(),
        $weddingUser->getRole());
  }
}