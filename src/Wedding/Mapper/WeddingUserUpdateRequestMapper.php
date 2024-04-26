<?php

namespace App\Wedding\Mapper;

use App\Wedding\Dto\WeddingUserUpdateRequest;
use App\Wedding\Entity\WeddingUser;

class WeddingUserUpdateRequestMapper {

  public static function map(?WeddingUser $weddingUser): ?WeddingUserUpdateRequest {
    if ($weddingUser === null) {
      return null;
    }

    return (new WeddingUserUpdateRequest())
        ->setUsername($weddingUser->getUser()->getUsername())
        ->setUserEmail($weddingUser->getUser()->getEmail())
        ->setRole($weddingUser->getRole());
  }
}