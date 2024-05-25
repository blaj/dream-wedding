<?php

namespace App\Common\Pagination\Dto;

/**
 * @template F of object
 */
class PaginatedListCriteria {

  /**
   * @var F|null
   */
  protected $filter = null;
  protected ?PageCriteria $pageCriteria = null;
  protected ?Sort $sort = null;

  /**
   * @param class-string<F> $filterClassName
   * @param F|null $filter
   */
  public function __construct(
    /** @phpstan-ignore-line */ private readonly string $filterClassName,
      $filter = null) {
    $this->filter = $filter;
  }

  /**
   * @return F|null
   */
  public function getFilter(): ?object {
    return $this->filter;
  }

  /**
   * @param F|null $filter
   *
   * @return $this<F>
   */
  public function setFilter(?object $filter): self {
    $this->filter = $filter;

    return $this;
  }

  public function getPageCriteria(): ?PageCriteria {
    return $this->pageCriteria ?? PageCriteria::default();
  }

  /**
   * @return $this<F>
   */
  public function setPageCriteria(?PageCriteria $pageCriteria): self {
    $this->pageCriteria = $pageCriteria;

    return $this;
  }

  public function getSort(): ?Sort {
    return $this->sort ?? Sort::empty();
  }

  /**
   * @return $this<F>
   */
  public function setSort(?Sort $sort): self {
    $this->sort = $sort;

    return $this;
  }
}