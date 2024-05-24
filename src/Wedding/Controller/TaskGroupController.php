<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Const\TranslationConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\TaskGroupCreateRequest;
use App\Wedding\Form\Type\TaskGroupCreateFormType;
use App\Wedding\Form\Type\TaskGroupUpdateFormType;
use App\Wedding\Service\TaskGroupService;
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
#[Route(path: '/{_locale}/wedding/{weddingId}/task-group', name: 'wedding_task_group_', requirements: ['weddingId' => '\d+', '_locale' => TranslationConst::availableLocales])]
class TaskGroupController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly TaskGroupService $taskGroupService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/task-group/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'taskGroupsListItemDto' => $this->taskGroupService->getList(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $taskGroupDetailsDto = $this->taskGroupService->getOne($id, $userData->getUserId());

    if ($taskGroupDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/task-group/details/details.html.twig',
        ['taskGroupDetailsDto' => $taskGroupDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            TaskGroupCreateFormType::class,
            $taskCreateRequest = new TaskGroupCreateRequest(),
            ['weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->taskGroupService->create($weddingId, $taskCreateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('task-group-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_task_group_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_task_group_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/task-group/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $taskUpdateRequest = $this->taskGroupService->getUpdateRequest($id, $userData->getUserId());

    if ($taskUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            TaskGroupUpdateFormType::class,
            $taskUpdateRequest,
            ['method' => 'PUT', 'weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->taskGroupService->update($id, $taskUpdateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('task-group-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_task_group_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/task-group/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->taskGroupService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('task-deleted-successfully'));

    return $this->redirectToRoute('wedding_task_group_list', ['weddingId' => $weddingId]);
  }
}
