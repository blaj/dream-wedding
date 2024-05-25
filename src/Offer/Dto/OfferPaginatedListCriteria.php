<?php

namespace App\Offer\Dto;

use App\Common\Pagination\Dto\PaginatedListCriteria;
use App\Common\Pagination\Validator\AllowedSortByField;

/**
 * @extends PaginatedListCriteria<OfferPaginatedListFilter>
 */
#[AllowedSortByField(fields: self::sortableFields)]
class OfferPaginatedListCriteria extends PaginatedListCriteria {

  const sortableFields = ['id'];

  public function __construct($filter = null) {
    parent::__construct(OfferPaginatedListFilter::class, $filter);
  }
}