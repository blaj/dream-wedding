<?php

namespace App\Common\Pagination\Mapper;

use App\Common\Pagination\Dto\Order;
use App\Common\Pagination\Dto\Sort;

class SortMapper {

  public static function map(?Sort $sort, string $defaultFieldSort): string {
    $sortBy = $defaultFieldSort;
    $direction = '';

    if ($sort !== null) {
      $sortBy = $sort->getBy() ?? $defaultFieldSort;
      $direction = $sort->getOrder() ?? '';
    }

    return sprintf(
        ' ORDER BY %s %s ',
        $sortBy,
        ($direction === '' || $direction === Order::ASC ? Order::ASC->value : Order::DESC->value));
  }
}