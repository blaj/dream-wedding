<?php

namespace App\Offer\Dto;

class OfferPaginatedListFilter {

  private ?string $searchBy = null;

  /**
   * @var array<int>
   */
  private array $categories = [];

  public function getSearchBy(): ?string {
    return $this->searchBy;
  }

  public function setSearchBy(?string $searchBy): self {
    $this->searchBy = $searchBy;

    return $this;
  }

  /**
   * @return array<int>
   */
  public function getCategories(): array {
    return $this->categories;
  }

  /**
   * @param array<int> $categories
   */
  public function setCategories(array $categories): self {
    $this->categories = $categories;

    return $this;
  }
}
