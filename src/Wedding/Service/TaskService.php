<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\TaskCreateRequest;
use App\Wedding\Dto\TaskDetailsDto;
use App\Wedding\Dto\TaskListItemDto;
use App\Wedding\Dto\TaskUpdateRequest;
use App\Wedding\Entity\Task;
use App\Wedding\Mapper\TaskDetailsDtoMapper;
use App\Wedding\Mapper\TaskListItemDtoMapper;
use App\Wedding\Mapper\TaskUpdateRequestMapper;
use App\Wedding\Repository\TaskRepository;

class TaskService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly TaskFetchService $taskFetchService,
      private readonly TaskRepository $taskRepository) {}

  /**
   * @return array<TaskListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Task $task) => TaskListItemDtoMapper::map($task),
            $this->taskRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?TaskListItemDto $dto) => $dto !== null);
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
        ->setWedding($wedding);

    $this->taskRepository->save($task);
  }

  public function update(int $id, TaskUpdateRequest $taskUpdateRequest, int $userId): void {
    $task = $this->taskFetchService->fetchTask($id, $userId);

    $task
        ->setName($taskUpdateRequest->getName())
        ->setDescription($taskUpdateRequest->getDescription())
        ->setOnDate($taskUpdateRequest->getOnDate());

    $this->taskRepository->save($task);
  }

  public function delete(int $id, int $userId): void {
    $this->taskRepository->softDeleteById(
        $this->taskFetchService->fetchTask($id, $userId)->getId());
  }
}