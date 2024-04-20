<?php

namespace App\Wedding\Controller;

use App\Security\Dto\UserData;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
}