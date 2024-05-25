<?php

namespace App\Common\Pagination\Service;

use App\Common\Pagination\Dto\Page;
use App\Common\Pagination\Dto\PaginatedList;
use App\Common\Pagination\Dto\PaginatedListCriteria;
use Doctrine\ORM\Query;

class PaginationService {

  /**
   * @template T
   * @template F of object
   *
   * @param class-string<T> $className
   * @param class-string<F> $filterClassName
   * @param PaginatedListCriteria<F> $paginatedListCriteria
   *
   * @return PaginatedList<T>
   */
  public function paginate(
      string $className,
      string $filterClassName,
      Query $query,
      Query $countQuery,
      PaginatedListCriteria $paginatedListCriteria,
      ?callable $filterCallable = null,
      ?callable $mapCallable = null): PaginatedList {
    $pageCriteria = $paginatedListCriteria->getPageCriteria();

    $offset =
        ($pageCriteria?->getNo() !== null ? $pageCriteria->getNo() : 0)
        * ($pageCriteria?->getSize() !== null ? $pageCriteria->getSize() : 0);

    $query
        ->setMaxResults($pageCriteria?->getSize())
        ->setFirstResult($offset);

    $filteredItemsQuery = clone $countQuery;

    if ($filterCallable !== null && $paginatedListCriteria->getFilter() !== null) {
      $filterCallable($query, $paginatedListCriteria->getFilter());
      $filterCallable($filteredItemsQuery, $paginatedListCriteria->getFilter());
    }

    $result = (array) $query->getResult();

    if ($mapCallable !== null) {
      $result = array_map($mapCallable, $result);
    }

    $totalItems = (int) $countQuery->getSingleScalarResult();
    $filteredItems = (int) $filteredItemsQuery->getSingleScalarResult();

    return (new PaginatedList($className))
        ->setItems($result)
        ->setSort($paginatedListCriteria->getSort())
        ->setPage(
            (new Page())
                ->setSize($pageCriteria?->getSize())
                ->setNo($pageCriteria?->getNo())
                ->setTotalItems($totalItems)
                ->setFilteredItems($filteredItems));
  }
}