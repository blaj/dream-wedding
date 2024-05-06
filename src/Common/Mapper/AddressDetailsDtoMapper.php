<?php

namespace App\Common\Mapper;

use App\Common\Dto\AddressDetailsDto;
use App\Common\Entity\Address;

class AddressDetailsDtoMapper {

  public static function map(?Address $address): ?AddressDetailsDto {
    if ($address === null) {
      return null;
    }

    return new AddressDetailsDto(
        $address->getCity(),
        $address->getStreet(),
        $address->getPostcode());
  }
}