<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\CostEstimateGroupCreateRequest;
use App\Wedding\Form\Type\CostEstimateGroupCreateFormType;
use App\Wedding\Form\Type\CostEstimateGroupUpdateFormType;
use App\Wedding\Service\CostEstimateGroupService;
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
#[Route(path: '/wedding/{weddingId}/cost-estimate-group', name: 'wedding_cost_estimate_group_', requirements: ['weddingId' => '\d+'])]
class CostEstimateGroupController extends AbstractController {

  public function __construct(
      private readonly CostEstimateGroupService $costEstimateGroupService,
      private readonly WeddingService $weddingService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/cost-estimate-group/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'costEstimateGroupsListItemDto' => $this->costEstimateGroupService->getList(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $costEstimateGroupDetailsDto =
        $this->costEstimateGroupService->getOne($id, $userData->getUserId());

    if ($costEstimateGroupDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/cost-estimate-group/details/details.html.twig',
        ['costEstimateGroupDetailsDto' => $costEstimateGroupDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            CostEstimateGroupCreateFormType::class,
            $guestGroupCreateRequest = new CostEstimateGroupCreateRequest(),
            ['weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->costEstimateGroupService->create(
          $weddingId,
          $guestGroupCreateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('cost-estimate-group-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_cost_estimate_group_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute(
              'wedding_cost_estimate_group_create',
              ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/cost-estimate-group/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $costEstimateGroupUpdateRequest =
        $this->costEstimateGroupService->getUpdateRequest($id, $userData->getUserId());

    if ($costEstimateGroupUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            CostEstimateGroupUpdateFormType::class,
            $costEstimateGroupUpdateRequest,
            ['method' => 'PUT', 'weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->costEstimateGroupService->update(
          $id,
          $costEstimateGroupUpdateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('cost-estimate-group-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_cost_estimate_group_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/cost-estimate-group/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->costEstimateGroupService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('cost-estimate-group-deleted-successfully'));

    return $this->redirectToRoute('wedding_cost_estimate_group_list', ['weddingId' => $weddingId]);
  }
}