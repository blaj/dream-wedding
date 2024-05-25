<?php

namespace App\Common\Pagination\Dto;

/**
 * @template T
 */
class PaginatedList {

  /**
   * @var array<T>
   */
  private array $items = [];
  private ?Page $page = null;
  private ?Sort $sort = null;

  /**
   * @param class-string<T> $className
   */
  public function __construct(/** @phpstan-ignore-line */ private readonly string $className) {}

  /**
   * @return array<T>
   */
  public function getItems(): array {
    return $this->items;
  }

  /**
   * @param array<T> $items
   *
   * @return $this<T>
   */
  public function setItems(array $items): self {
    $this->items = $items;

    return $this;
  }

  public function getPage(): ?Page {
    return $this->page;
  }

  /**
   * @return $this<T>
   */
  public function setPage(?Page $page): self {
    $this->page = $page;

    return $this;
  }

  public function getSort(): ?Sort {
    return $this->sort;
  }

  /**
   * @return $this<T>
   */
  public function setSort(?Sort $sort): self {
    $this->sort = $sort;

    return $this;
  }
}