<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\TaskCreateRequest;
use App\Wedding\Form\Type\TaskCreateFormType;
use App\Wedding\Form\Type\TaskUpdateFormType;
use App\Wedding\Service\TaskGroupBuilderService;
use App\Wedding\Service\TaskService;
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
#[Route(path: '/wedding/{weddingId}/task', name: 'wedding_task_', requirements: ['weddingId' => '\d+'])]
class TaskController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly TaskService $taskService,
      private readonly TaskGroupBuilderService $taskGroupBuilderService) {}

  #[Route(path: '/', name: 'list', methods: ['GET'])]
  public function list(int $weddingId, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/task/list/list.html.twig',
        [
            'weddingDetailsDto' => $weddingDetailsDto,
            'ungroupedTasksListItemDto' => $this->taskService->getUngroupedList(
                $weddingId,
                $userData->getUserId()),
            'taskGroupBuildDto' => $this->taskGroupBuilderService->build(
                $weddingId,
                $userData->getUserId())]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $taskDetailsDto = $this->taskService->getOne($id, $userData->getUserId());

    if ($taskDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/task/details/details.html.twig',
        ['taskDetailsDto' => $taskDetailsDto, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $form =
        $this->createForm(
            TaskCreateFormType::class,
            $taskCreateRequest = new TaskCreateRequest(),
            ['weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->taskService->create($weddingId, $taskCreateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('task-created-successfully'));

      return FormUtils::getRedirectResponse(
          $form,
          $this->redirectToRoute('wedding_task_list', ['weddingId' => $weddingId]),
          $this->redirectToRoute('wedding_task_create', ['weddingId' => $weddingId]));
    }

    return $this->render(
        'wedding/task/create/create.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $taskUpdateRequest = $this->taskService->getUpdateRequest($id, $userData->getUserId());

    if ($taskUpdateRequest === null) {
      throw new NotFoundHttpException();
    }

    $form =
        $this->createForm(
            TaskUpdateFormType::class,
            $taskUpdateRequest,
            ['method' => 'PUT', 'weddingId' => $weddingId, 'userId' => $userData->getUserId()]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->taskService->update($id, $taskUpdateRequest, $userData->getUserId());

      $this->addFlash(
          FlashMessageConst::$success,
          new TranslatableMessage('task-updated-successfully'));

      return $this->redirectToRoute(
          'wedding_task_details',
          ['weddingId' => $weddingId, 'id' => $id]);
    }

    return $this->render(
        'wedding/task/update/update.html.twig',
        ['form' => $form, 'weddingId' => $weddingId]);
  }

  #[Route(path: '/{id}/delete', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
  public function delete(int $weddingId, int $id, UserData $userData): Response {
    $this->taskService->delete($id, $userData->getUserId());

    $this->addFlash(
        FlashMessageConst::$success,
        new TranslatableMessage('task-deleted-successfully'));

    return $this->redirectToRoute('wedding_task_list', ['weddingId' => $weddingId]);
  }
}