<?php

namespace App\Common\Pagination\Dto;

class Sort {

  public static function empty(): Sort {
    return (new Sort())->setBy(null)->setOrder(null);
  }

  private ?string $by = null;
  private ?Order $order = null;

  public function getBy(): ?string {
    return $this->by;
  }

  public function setBy(?string $by): self {
    $this->by = $by;

    return $this;
  }

  public function getOrder(): ?Order {
    return $this->order;
  }

  public function setOrder(?Order $order): self {
    $this->order = $order;

    return $this;
  }
}