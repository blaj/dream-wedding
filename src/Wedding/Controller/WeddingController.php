<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Security\Dto\UserData;
use App\Wedding\Dto\WeddingCreateRequest;
use App\Wedding\Form\Type\WeddingCreateFormType;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding', name: 'wedding_')]
class WeddingController extends AbstractController {

  public function __construct(private readonly WeddingService $weddingService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(UserData $userData): Response {
    return $this->render(
        'wedding/list/list.html.twig',
        ['weddingsListItemDto' => $this->weddingService->getList($userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $id, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($id, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/details/details.html.twig',
        ['weddingDetailsDto' => $weddingDetailsDto]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(UserData $userData, Request $request): Response {
    $form =
        $this->createForm(
            WeddingCreateFormType::class,
            $weddingCreateRequest = new WeddingCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->weddingService->create($weddingCreateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('wedding-created-successfully'));

      return $this->redirectToRoute('wedding_list');
    }

    return $this->render('wedding/create/create.html.twig', ['form' => $form]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $id): Response {
    return $this->render('wedding/update/update.html.twig');
  }
}