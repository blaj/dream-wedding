<?php

namespace App\Wedding\Dto;

class GuestCreateManyRequest {

  /**
   * @var array<GuestCreateManyRowRequest>
   */
  private array $rows = [];

  public function __construct() {
    $this->rows = [
        new GuestCreateManyRowRequest(),
        new GuestCreateManyRowRequest(),
        new GuestCreateManyRowRequest()
    ];
  }

  /**
   * @return array<GuestCreateManyRowRequest>
   */
  public function getRows(): array {
    return $this->rows;
  }

  /**
   * @param array<GuestCreateManyRowRequest> $rows
   */
  public function setRows(array $rows): self {
    $this->rows = $rows;

    return $this;
  }
}