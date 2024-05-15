<?php

namespace App\Wedding\Controller;

use App\Common\Dto\UpdateGroupRequest;
use App\Common\Dto\UpdateOrderNoRequest;
use App\Security\Dto\UserData;
use App\Wedding\Service\CostEstimateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(
    path: '/wedding/{weddingId}/cost-estimate-ajax',
    name: 'wedding_cost_estimate_ajax_',
    requirements: ['weddingId' => '\d+'])]
class CostEstimateAjaxController extends AbstractController {

  public function __construct(private readonly CostEstimateService $costEstimateService) {}

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
    $this->costEstimateService->updateGroup(
        $id,
        $updateGroupRequest->groupId,
        $userData->getUserId());

    return $this->json([], Response::HTTP_NO_CONTENT);
  }

  #[Route(
      path: '/{id}/update-order-no',
      name: 'update_order_no',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateOrderNo(
      int $weddingId,
      int $id,
      #[MapRequestPayload] UpdateOrderNoRequest $updateOrderNoRequest,
      UserData $userData): Response {
    $this->costEstimateService->updateOrderNo(
        $id,
        $updateOrderNoRequest->orderNo,
        $userData->getUserId());

    return $this->json([], Response::HTTP_NO_CONTENT);
  }
}