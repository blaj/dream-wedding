<?php

namespace App\Offer\Service;

use App\Offer\Entity\OfferCategory;
use App\Offer\Repository\OfferCategoryRepository;
use Doctrine\ORM\EntityNotFoundException;

class OfferCategoryFetchService {

  public function __construct(private readonly OfferCategoryRepository $offerCategoryRepository) {}

  public function fetchOfferCategory(int $id): OfferCategory {
    return $this->offerCategoryRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Offer category not found');
  }
}