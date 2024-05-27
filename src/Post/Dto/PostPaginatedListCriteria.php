<?php

namespace App\Post\Dto;

use App\Common\Pagination\Dto\EmptyPaginatedListFilter;
use App\Common\Pagination\Dto\PaginatedListCriteria;

/**
 * @extends PaginatedListCriteria<EmptyPaginatedListFilter>
 */
class PostPaginatedListCriteria extends PaginatedListCriteria {

  public function __construct() {
    parent::__construct(EmptyPaginatedListFilter::class, new EmptyPaginatedListFilter());
  }
}