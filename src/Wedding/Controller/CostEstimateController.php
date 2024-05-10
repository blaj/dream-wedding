<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\CostEstimateCreateRequest;
use App\Wedding\Form\Type\CostEstimateCreateFormType;
use App\Wedding\Form\Type\CostEstimateUpdateFormType;
use App\Wedding\Service\CostEstimateCalculationService;
use App\Wedding\Service\CostEstimateService;
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
#[Route(path: '/wedding/{weddingId}/cost-estimate', name: 'wedding_cost_estimate_', requirements: ['weddingId' => '\d+'])]
class CostEstimateController extends AbstractController {

  public function __construct(
      private readonly CostEstimateService $costEstimateService,
      private readonly CostEstimateCalculationService $costEstimateCalculationService,
      private readonly WeddingService $weddingService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/cost-estimate/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'costEstimatesListItemDto' => $this->costEstimateService->getList(
                $weddingId,
                $userData->getUserId()),
            'costEstimateCalculatedDto' => $this->costEstimateCalculationService->calculate(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $costEstimateDetailsDto =
        $this->costEstimateService->getOne($id, $userData->getUserId());

    if ($costEstimateDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/cost-estimate/details/details.html.twig',
        [
            'costEstimateDetailsDto' => $costEstimateDetailsDto,
            'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            CostEstimateCreateFormType::class,
            $costEstimateCreateRequest = new CostEstimateCreateRequest(),
            ['weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->costEstimateService->create(
          $weddingId,
          $costEstimateCreateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('cost-estimate-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_cost_estimate_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_cost_estimate_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/cost-estimate/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $costEstimateUpdateRequest =
        $this->costEstimateService->getUpdateRequest($id, $userData->getUserId());

    if ($costEstimateUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            CostEstimateUpdateFormType::class,
            $costEstimateUpdateRequest,
            ['method' => 'PUT', 'weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->costEstimateService->update(
          $id,
          $costEstimateUpdateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('cost-estimate-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_cost_estimate_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/cost-estimate/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->costEstimateService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('cost-estimate-deleted-successfully'));

    return $this->redirectToRoute('wedding_cost_estimate_list', ['weddingId' => $weddingId]);
  }
}