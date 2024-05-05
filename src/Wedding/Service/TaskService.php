<?php

namespace App\Wedding\Service;

use App\Common\Dto\FullCalendarEventDto;
use App\Common\Dto\FullCalendarQueryDto;
use App\Wedding\Dto\TaskCreateRequest;
use App\Wedding\Dto\TaskDetailsDto;
use App\Wedding\Dto\TaskListItemDto;
use App\Wedding\Dto\TaskUpdateRequest;
use App\Wedding\Entity\Task;
use App\Wedding\Mapper\TaskDetailsDtoMapper;
use App\Wedding\Mapper\TaskListItemDtoMapper;
use App\Wedding\Mapper\TaskUpdateRequestMapper;
use App\Wedding\Repository\TaskRepository;
use Symfony\Component\Routing\RouterInterface;

class TaskService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly TaskFetchService $taskFetchService,
      private readonly TaskRepository $taskRepository,
      private readonly RouterInterface $router) {}

  /**
   * @return array<TaskListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Task $task) => TaskListItemDtoMapper::map($task),
            $this->taskRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?TaskListItemDto $dto) => $dto !== null);
  }

  /**
   * @return array<TaskListItemDto>
   */
  public function getUngroupedList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Task $task) => TaskListItemDtoMapper::map($task),
            $this->taskRepository->findAllByWeddingIdAndUserIdAndGroupIsNull($weddingId, $userId)),
        fn (?TaskListItemDto $dto) => $dto !== null);
  }

  /**
   * @return array<FullCalendarEventDto>
   */
  public function getFullCalendarList(
      int $weddingId,
      FullCalendarQueryDto $fullCalendarQueryDto,
      int $userId): array {
    return array_map(
        fn (Task $task) => new FullCalendarEventDto(
            $task->getId(),
            $task->getOnDate(),
            $task->getOnDate(),
            $this->router->generate(
                'wedding_task_details',
                ['weddingId' => $weddingId, 'id' => $task->getId()]),
            $task->getName(),
            $task->getDescription(),
            $task->getColor() !== null ? $task->getColor() : $task->getGroup()?->getColor()),
        $this->taskRepository->findAllByWeddingIdAndQueryAndUserId(
            $weddingId,
            $fullCalendarQueryDto,
            $userId));
  }

  public function getOne(int $id, int $userId): ?TaskDetailsDto {
    return TaskDetailsDtoMapper::map($this->taskRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?TaskUpdateRequest {
    return TaskUpdateRequestMapper::map($this->taskRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(int $weddingId, TaskCreateRequest $taskCreateRequest, int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);

    $task = (new Task())
        ->setName($taskCreateRequest->getName())
        ->setDescription($taskCreateRequest->getDescription())
        ->setOnDate($taskCreateRequest->getOnDate())
        ->setWedding($wedding)
        ->setColor($taskCreateRequest->isSetColor() ? $taskCreateRequest->getColor() : null)
        ->setCompleted($taskCreateRequest->isCompleted());

    $this->taskRepository->save($task);
  }

  public function update(int $id, TaskUpdateRequest $taskUpdateRequest, int $userId): void {
    $task = $this->taskFetchService->fetchTask($id, $userId);

    $task
        ->setName($taskUpdateRequest->getName())
        ->setDescription($taskUpdateRequest->getDescription())
        ->setOnDate($taskUpdateRequest->getOnDate())
        ->setColor($taskUpdateRequest->isSetColor() ? $taskUpdateRequest->getColor() : null)
        ->setCompleted($taskUpdateRequest->isCompleted());

    $this->taskRepository->save($task);
  }

  public function updateCompleted(int $id, bool $completed, int $userId): void {
    $task = $this->taskFetchService->fetchTask($id, $userId);

    $task->setCompleted($completed);

    $this->taskRepository->save($task);
  }

  public function delete(int $id, int $userId): void {
    $this->taskRepository->softDeleteById(
        $this->taskFetchService->fetchTask($id, $userId)->getId());
  }
}