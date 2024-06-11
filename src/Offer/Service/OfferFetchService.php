<?php

namespace App\Offer\Service;

use App\Offer\Entity\Offer;
use App\Offer\Repository\OfferRepository;
use Doctrine\ORM\EntityNotFoundException;

class OfferFetchService {

  public function __construct(private readonly OfferRepository $offerRepository) {}

  public function fetchOffer(int $id): Offer {
    return $this->offerRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Offer not found');
  }
}