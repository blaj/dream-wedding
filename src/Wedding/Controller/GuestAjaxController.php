<?php

namespace App\Wedding\Controller;

use App\Common\Dto\UpdateGroupRequest;
use App\Common\Dto\UpdateOrderNoRequest;
use App\Security\Dto\UserData;
use App\Wedding\Service\GuestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding/{weddingId}/guest-ajax', name: 'wedding_guest_ajax_', requirements: ['weddingId' => '\d+'])]
class GuestAjaxController extends AbstractController {

  public function __construct(private readonly GuestService $guestService) {}

  #[Route(
      path: '/{id}/update-group',
      name: 'update_group',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateGroup(
      int $weddingId,
      int $id,
      #[MapRequestPayload] UpdateGroupRequest $updateGroupRequest,
      UserData $userData): Response {
    $this->guestService->updateGroup(
        $id,
        $updateGroupRequest->groupId,
        $userData->getUserId());

    return $this->json([]);
  }

  #[Route(
      path: '/{id}/update-group-order-no',
      name: 'update_group_order_no',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateOrderNo(
      int $weddingId,
      int $id,
      #[MapRequestPayload] UpdateOrderNoRequest $updateOrderNoRequest,
      UserData $userData): Response {
    $this->guestService->updateGroupOrderNo(
        $id,
        $updateOrderNoRequest->orderNo,
        $userData->getUserId());

    return $this->json([]);
  }

  #[Route(
      path: '/{id}/update-table',
      name: 'update_table',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateTable(
      int $weddingId,
      int $id,
      #[MapRequestPayload] UpdateGroupRequest $updateGroupRequest,
      UserData $userData): Response {
    $this->guestService->updateTable(
        $id,
        $updateGroupRequest->groupId,
        $userData->getUserId());

    return $this->json([]);
  }

  #[Route(
      path: '/{id}/update-table-order-no',
      name: 'update_table_order_no',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateTableOrderNo(
      int $weddingId,
      int $id,
      #[MapRequestPayload] UpdateOrderNoRequest $updateOrderNoRequest,
      UserData $userData): Response {
    $this->guestService->updateTableOrderNo(
        $id,
        $updateOrderNoRequest->orderNo,
        $userData->getUserId());

    return $this->json([]);
  }
}