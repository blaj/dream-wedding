<?php

namespace App\Wedding\Mapper;

use App\Common\Dto\AddressRequest;
use App\Wedding\Dto\WeddingUpdateRequest;
use App\Wedding\Entity\Wedding;

class WeddingUpdateRequestMapper {

  public static function map(?Wedding $wedding): ?WeddingUpdateRequest {
    if ($wedding === null) {
      return null;
    }

    return (new WeddingUpdateRequest())
        ->setName($wedding->getName())
        ->setOnDate($wedding->getOnDate())
        ->setBudget($wedding->getBudget())
        ->setWeddingAddress(
            (new AddressRequest())
                ->setCity($wedding->getWeddingAddress()->getCity())
                ->setStreet($wedding->getWeddingAddress()->getStreet())
                ->setPostcode($wedding->getWeddingAddress()->getPostcode()))
        ->setPartyAddress(
            (new AddressRequest())
                ->setCity($wedding->getPartyAddress()->getCity())
                ->setStreet($wedding->getPartyAddress()->getStreet())
                ->setPostcode($wedding->getPartyAddress()->getPostcode()));
  }
}