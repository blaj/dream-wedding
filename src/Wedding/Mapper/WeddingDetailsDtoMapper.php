<?php

namespace App\Wedding\Mapper;

use App\Common\Mapper\AddressDetailsDtoMapper;
use App\Wedding\Dto\WeddingDetailsDto;
use App\Wedding\Entity\Wedding;

class WeddingDetailsDtoMapper {

  public static function map(?Wedding $wedding): ?WeddingDetailsDto {
    if ($wedding === null) {
      return null;
    }

    return new WeddingDetailsDto(
        $wedding->getId(),
        $wedding->getName(),
        $wedding->getOnDate(),
        $wedding->getBudget(),
        AddressDetailsDtoMapper::map($wedding->getWeddingAddress()),
        AddressDetailsDtoMapper::map($wedding->getPartyAddress()));
  }
}