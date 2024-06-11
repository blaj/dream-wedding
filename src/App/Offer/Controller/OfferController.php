<?php

namespace App\App\Offer\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Offer\Dto\OfferCreateRequest;
use App\Offer\Dto\OfferPaginatedListCriteria;
use App\Offer\Dto\OfferPaginatedListFilter;
use App\Offer\Form\Type\OfferCreateFormType;
use App\Offer\Form\Type\OfferPaginatedListCriteriaFormType;
use App\Offer\Form\Type\OfferUpdateFormType;
use App\Offer\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted('ROLE_MODERATOR')]
#[Route(path: '/offer', name: 'offer_')]
class OfferController extends AbstractController {

  public function __construct(private readonly OfferService $offerService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(Request $request): Response {
    $paginatedListCriteriaForm =
        $this->createForm(
            OfferPaginatedListCriteriaFormType::class,
            $offerPaginatedListCriteria =
                new OfferPaginatedListCriteria(new OfferPaginatedListFilter()));
    $paginatedListCriteriaForm->handleRequest($request);

    return $this->render('app/offer/list/list.html.twig', [
        'paginatedListCriteriaForm' => $paginatedListCriteriaForm,
        'offerPaginatedListCriteria' => $offerPaginatedListCriteria,
        'paginatedOffersListItemDto' => $this->offerService->getPaginatedList(
            $offerPaginatedListCriteria)]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(Request $request): Response {
    $form =
        $this->createForm(OfferCreateFormType::class, $offerCreateRequest = new OfferCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->offerService->create($offerCreateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('offer-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('app_offer_list'),
          $this->redirectToRoute('app_offer_create'));
    }

    return $this->render('app/offer/create/create.html.twig', ['form' => $form]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $id, Request $request): Response {
    $offerDetailsDto = $this->offerService->getOne($id);

    if ($offerDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    $offerUpdateRequest = $this->offerService->getUpdateRequest($id);

    if ($offerUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form = $this->createForm(OfferUpdateFormType::class, $offerUpdateRequest, ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->offerService->update($id, $offerUpdateRequest);

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('offer-updated-successfully'));

      return $this->redirectToRoute('app_offer_list');
    }

    return $this->render(
        'app/offer/update/update.html.twig',
        [
            'form' => $form,
            'offerUpdateRequest' => $offerUpdateRequest,
            'offerDetailsDto' => $offerDetailsDto]);
  }

  #[Route(
      path: '/{id}/delete',
      name: 'delete',
      requirements: ['id' => '\d+'],
      methods: ['DELETE'])]
  public function delete(int $id): Response {
    $this->offerService->delete($id);

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('offer-deleted-successfully'));

    return $this->redirectToRoute('app_offer_list');
  }
}