<?php

namespace App\Wedding\Controller;

use App\Common\Dto\UpdateNameRequest;
use App\Security\Dto\UserData;
use App\Wedding\Service\GuestGroupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(
    path: '/wedding/{weddingId}/guest-group-ajax',
    name: 'wedding_guest_group_ajax_',
    requirements: ['weddingId' => '\d+'])]
class GuestGroupAjaxController extends AbstractController {

  public function __construct(private readonly GuestGroupService $guestGroupService) {}

  #[Route(
      path: '/{id}/update-name',
      name: 'update_name',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateName(
      int $weddingId,
      int $id,
      #[MapRequestPayload] UpdateNameRequest $updateNameRequest,
      UserData $userData): Response {
    $this->guestGroupService->updateName(
        $id,
        $updateNameRequest->name,
        $userData->getUserId());

    return $this->json([], Response::HTTP_NO_CONTENT);
  }
}