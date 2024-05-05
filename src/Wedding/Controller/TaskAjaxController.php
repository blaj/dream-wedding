<?php

namespace App\Wedding\Controller;

use App\Common\Dto\FullCalendarQueryDto;
use App\Security\Dto\UserData;
use App\Wedding\Dto\TaskUpdateCompletedRequest;
use App\Wedding\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression("is_authenticated()"))]
#[Route(path: '/wedding/{weddingId}/task-ajax', name: 'wedding_task_ajax_', requirements: ['weddingId' => '\d+'])]
class TaskAjaxController extends AbstractController {

  public function __construct(private readonly TaskService $taskService) {}

  #[Route(path: '/', name: 'list', options: ['expose' => true], methods: ['GET'])]
  public function list(
      #[MapQueryString] FullCalendarQueryDto $fullCalendarQueryDto,
      int $weddingId,
      UserData $userData): Response {
    return $this->json(
        $this->taskService->getFullCalendarList(
            $weddingId,
            $fullCalendarQueryDto,
            $userData->getUserId()));
  }

  #[Route(
      path: '/{id}/update-completed',
      name: 'update_completed',
      requirements: ['id' => '\d+'],
      options: ['expose' => true],
      methods: ['PUT'])]
  public function updateCompleted(
      int $weddingId,
      int $id,
      #[MapRequestPayload] TaskUpdateCompletedRequest $taskUpdateCompletedRequest,
      UserData $userData): Response {
    $this->taskService->updateCompleted(
        $id,
        $taskUpdateCompletedRequest->isCompleted(),
        $userData->getUserId());

    return $this->json([]);
  }
}