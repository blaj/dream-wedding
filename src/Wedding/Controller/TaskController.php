<?php

namespace App\Wedding\Controller;

use App\Common\Const\FlashMessageConst;
use App\Common\Const\TranslationConst;
use App\Common\Dto\GroupSimpleCreateRequest;
use App\Common\Form\Type\GroupSimpleCreateFormType;
use App\Common\Utils\FormUtils;
use App\Security\Dto\UserData;
use App\Wedding\Dto\TaskCreateRequest;
use App\Wedding\Form\Type\TaskCreateFormType;
use App\Wedding\Form\Type\TaskUpdateFormType;
use App\Wedding\Service\TaskGroupBuilderService;
use App\Wedding\Service\TaskGroupService;
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
use Symfony\UX\Turbo\TurboBundle;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/{_locale}/wedding/{weddingId}/task', name: 'wedding_task_', requirements: [
    'weddingId' => '\d+',
    '_locale' => TranslationConst::availableLocales])]
class TaskController extends AbstractController {

  public function __construct(
      private readonly WeddingService $weddingService,
      private readonly TaskService $taskService,
      private readonly TaskGroupService $taskGroupService,
      private readonly TaskGroupBuilderService $taskGroupBuilderService) {}

  #[Route(path: '/', name: 'list', methods: ['GET', 'POST'])]
  public function list(int $weddingId, Request $request, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    $groupSimpleCreateForm =
        $this->createForm(
            GroupSimpleCreateFormType::class,
            $groupSimpleCreateRequest = new GroupSimpleCreateRequest());
    $emptyGroupSimpleCreateForm = clone $groupSimpleCreateForm;
    $groupSimpleCreateForm->handleRequest($request);

    if ($groupSimpleCreateForm->isSubmitted() && $groupSimpleCreateForm->isValid()) {
      if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        $createdGroupId =
            $this->taskGroupService->simpleCreate(
                $weddingId,
                $groupSimpleCreateRequest,
                $userData->getUserId());

        return $this->renderBlock(
            'wedding/task/list/list.html.twig',
            'success_create_group_stream',
            [
                'weddingId' => $weddingId,
                'groupSimpleCreateForm' => $emptyGroupSimpleCreateForm,
                'createdTaskGroupDetailsDto' => $this->taskGroupService->getOne(
                    $createdGroupId,
                    $userData->getUserId())]);
      }

      return $this->redirectToRoute(
          'wedding_task_list',
          ['weddingId' => $weddingId],
          Response::HTTP_SEE_OTHER);
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
                $userData->getUserId()),
            'groupSimpleCreateForm' => $groupSimpleCreateForm]);
  }

  #[Route(path: '/{id}', name: 'details', requirements: ['id' => '\d+'], methods: ['GET'])]
  public function details(int $weddingId, int $id, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    $taskDetailsDto = $this->taskService->getOne($id, $userData->getUserId());

    if ($taskDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    return $this->render(
        'wedding/task/details/details.html.twig',
        ['taskDetailsDto' => $taskDetailsDto, 'weddingDetailsDto' => $weddingDetailsDto]);
  }

  #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
  public function create(int $weddingId, Request $request, UserData $userData): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

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
        ['form' => $form, 'weddingDetailsDto' => $weddingDetailsDto]);
  }

  #[Route(
      path: '/{id}/update',
      name: 'update',
      requirements: ['id' => '\d+'],
      methods: ['GET', 'PUT'])]
  public function update(int $weddingId, int $id, UserData $userData, Request $request): Response {
    $weddingDetailsDto = $this->weddingService->getOne($weddingId, $userData->getUserId());

    if ($weddingDetailsDto === null) {
      throw new NotFoundHttpException();
    }

    $taskDetailsDto = $this->taskService->getOne($id, $userData->getUserId());

    if ($taskDetailsDto === null) {
      throw new NotFoundHttpException();
    }

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
        [
            'form' => $form,
            'weddingDetailsDto' => $weddingDetailsDto,
            'taskDetailsDto' => $taskDetailsDto]);
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