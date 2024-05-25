<?php

namespace App\Offer\Controller;

use App\Common\Const\TranslationConst;
use App\Offer\Dto\OfferPaginatedListCriteria;
use App\Offer\FormType\OfferPaginatedListCriteriaFormType;
use App\Offer\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/{_locale}/offer', name: 'offer_', requirements: ['_locale' => TranslationConst::availableLocales])]
class OfferController extends AbstractController {

  public function __construct(private readonly OfferService $offerService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(Request $request): Response {
    $paginatedListCriteriaForm =
        $this->createForm(
            OfferPaginatedListCriteriaFormType::class,
            $offerPaginatedListCriteria = new OfferPaginatedListCriteria());
    $paginatedListCriteriaForm->handleRequest($request);

    return $this->render(
        'offer/list/list.html.twig',
        [
            'paginatedListCriteriaForm' => $paginatedListCriteriaForm,
            'offerPaginatedListCriteria' => $offerPaginatedListCriteria,
            'offersCount' => $this->offerService->getCount(),
            'paginatedOffersListItemDto' => $this->offerService->getPaginatedList(
                $offerPaginatedListCriteria)]);
  }
}