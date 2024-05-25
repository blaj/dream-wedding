<?php

namespace App\Common\Pagination\Dto;

class PageCriteria {

  public static int $defaultSize = 15;
  public static int $defaultNo = 0;

  public static function default(): PageCriteria {
    return (new PageCriteria())->setSize(self::$defaultSize)->setNo(self::$defaultNo);
  }

  private ?int $no = null;
  private ?int $size = null;

  public function getNo(): ?int {
    if ($this->no === 0) {
      return $this->no;
    }

    return $this->no !== null ? max($this->no, 0) : self::default()->getNo();
  }

  public function setNo(?int $no): self {
    $this->no = $no;

    return $this;
  }

  public function getSize(): ?int {
    if ($this->size === 0) {
      return $this->size;
    }

    return $this->size ?? self::default()->getSize();
  }

  public function setSize(?int $size): self {
    $this->size = $size;

    return $this;
  }
}