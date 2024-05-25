<?php

namespace App\Offer\Dto;

class OfferPaginatedListFilter {

  private ?string $searchBy = null;

  public function getSearchBy(): ?string {
    return $this->searchBy;
  }

  public function setSearchBy(?string $searchBy): self {
    $this->searchBy = $searchBy;

    return $this;
  }
}