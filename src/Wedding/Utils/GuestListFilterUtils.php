<?php

namespace App\Wedding\Utils;

use App\Wedding\Dto\GuestListFilterRequest;

class GuestListFilterUtils {

  public static function isFilterActive(GuestListFilterRequest $guestListFilterRequest): bool {
    return $guestListFilterRequest->getFirstName() !== null
        || $guestListFilterRequest->getLastName() !== null
        || $guestListFilterRequest->getInvited() !== null
        || $guestListFilterRequest->getConfirmed() !== null
        || $guestListFilterRequest->getAccommodation() !== null
        || $guestListFilterRequest->getTransport() !== null
        || $guestListFilterRequest->getDietType() !== null;
  }
}