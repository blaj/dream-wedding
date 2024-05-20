<?php

namespace App\Wedding\Controller;

use App\Security\Dto\UserData;
use App\Wedding\Dto\GuestCreateManyRequest;
use App\Wedding\Dto\GuestListFilterRequest;
use App\Wedding\Form\Type\GuestCreateManyFormType;
use App\Wedding\Form\Type\GuestListFilterFormType;
use App\Wedding\Service\GuestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding/{weddingId}/guest-modal', name: 'wedding_guest_modal_', requirements: ['weddingId' => '\d+'])]
class GuestModalController extends AbstractController {

  public function __construct(private readonly GuestService $guestService) {}

  #[Route(path: '/create-many', name: 'create_many', methods: ['GET', 'POST'])]
  public function createMany(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            GuestCreateManyFormType::class,
            $guestCreateManyRequest = new GuestCreateManyRequest(),
            [
                'action' => $this->generateUrl(
                    'wedding_guest_modal_create_many',
                    ['weddingId' => $weddingId])]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->guestService->createMany($weddingId, $guestCreateManyRequest, $userData->getUserId());

      return $this->redirectToRoute('wedding_guest_list', ['weddingId' => $weddingId]);
    }

    return $this->render(
        'wedding/guest/modal/many-create.html.twig',
        ['form' => $form]);
  }

  #[Route(path: '/filter', name: 'filter', methods: ['GET'])]
  public function filter(int $weddingId, Request $request): Response {
    $form =
        $this->createForm(
            GuestListFilterFormType::class,
            $guestListFilterRequest = new GuestListFilterRequest(),
            [
                'method' => 'GET',
                'action' => $this->generateUrl(
                    'wedding_guest_modal_filter',
                    ['weddingId' => $weddingId])]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      return $this->redirect(
          $this->generateUrl('wedding_guest_list', ['weddingId' => $weddingId])
          . '?'
          . $request->getQueryString());
    }

    return $this->render('wedding/guest/modal/filter.html.twig', ['form' => $form]);
  }
}