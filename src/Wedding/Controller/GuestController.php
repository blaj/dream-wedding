<?php

namespace App\Wedding\Controller;

use App\Security\Dto\UserData;
use App\Wedding\Service\GuestService;
use App\Wedding\Service\WeddingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding/{weddingId}/guest', name: 'wedding_guest_', requirements: ['weddingId' => '\d+'])]
class GuestController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly GuestService $guestService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/guest/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'guestsListItemDto' => $this->guestService->getList(
                $weddingId,
                $userData->getUserId())]);
  }
}