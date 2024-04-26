<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\WeddingCostEstimateCreateRequest;
use App\Wedding\Form\Type\WeddingCostEstimateCreateFormType;
use App\Wedding\Form\Type\WeddingCostEstimateUpdateFormType;
use App\Wedding\Service\WeddingCostEstimateService;
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
class WeddingCostEstimateController extends AbstractController {

  public function __construct(
      private readonly WeddingCostEstimateService $weddingCostEstimateService,
      private readonly WeddingService $weddingService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/wedding-cost-estimate/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'weddingCostEstimatesListItemDto' => $this->weddingCostEstimateService->getList(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $weddingCostEstimateDetailsDto =
        $this->weddingCostEstimateService->getOne($id, $userData->getUserId());

    if ($weddingCostEstimateDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/wedding-cost-estimate/details/details.html.twig',
        [
            'weddingCostEstimateDetailsDto' => $weddingCostEstimateDetailsDto,
            'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            WeddingCostEstimateCreateFormType::class,
            $weddingCostEstimateCreateRequest = new WeddingCostEstimateCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->weddingCostEstimateService->create(
          $weddingId,
          $weddingCostEstimateCreateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('wedding-cost-estimate-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_cost_estimate_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_cost_estimate_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/wedding-cost-estimate/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $weddingCostEstimateUpdateRequest =
        $this->weddingCostEstimateService->getUpdateRequest($id, $userData->getUserId());

    if ($weddingCostEstimateUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            WeddingCostEstimateUpdateFormType::class,
            $weddingCostEstimateUpdateRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->weddingCostEstimateService->update(
          $id,
          $weddingCostEstimateUpdateRequest,
          $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('wedding-cost-estimate-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_cost_estimate_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/wedding-cost-estimate/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->weddingCostEstimateService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('wedding-cost-estimate-deleted-successfully'));

    return $this->redirectToRoute('wedding_cost_estimate_list', ['weddingId' => $weddingId]);
  }
}