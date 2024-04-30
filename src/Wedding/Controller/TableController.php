<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\TableCreateRequest;
use App\Wedding\Form\Type\TableCreateFormType;
use App\Wedding\Form\Type\TableUpdateFormType;
use App\Wedding\Service\TableGuestBuilderService;
use App\Wedding\Service\TableService;
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
#[Route(path: '/wedding/{weddingId}/table', name: 'wedding_table_', requirements: ['weddingId' => '\d+'])]
class TableController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly TableService $tableService,
      private readonly TableGuestBuilderService $tableGuestBuilderService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/table/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'tableGuestBuildDto' => $this->tableGuestBuilderService->build(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $tableDetailsDto = $this->tableService->getOne($id, $userData->getUserId());

    if ($tableDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/table/details/details.html.twig',
        ['tableDetailsDto' => $tableDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            TableCreateFormType::class,
            $tableCreateRequest = new TableCreateRequest());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->tableService->create($weddingId, $tableCreateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('table-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_table_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_table_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/table/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $tableUpdateRequest = $this->tableService->getUpdateRequest($id, $userData->getUserId());

    if ($tableUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            TableUpdateFormType::class,
            $tableUpdateRequest,
            ['method' => 'PUT']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->tableService->update($id, $tableUpdateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('table-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_table_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/table/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->tableService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('table-deleted-successfully'));

    return $this->redirectToRoute('wedding_table_list', ['weddingId' => $weddingId]);
  }
}