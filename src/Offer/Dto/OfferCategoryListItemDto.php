<?php

namespace App\Offer\Dto;

readonly class OfferCategoryListItemDto {

  public function __construct(public int $id, public string $name) {}
}