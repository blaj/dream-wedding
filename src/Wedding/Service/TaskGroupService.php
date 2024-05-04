<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\TaskGroupCreateRequest;
use App\Wedding\Dto\TaskGroupDetailsDto;
use App\Wedding\Dto\TaskGroupListItemDto;
use App\Wedding\Dto\TaskGroupUpdateRequest;
use App\Wedding\Entity\Task;
use App\Wedding\Entity\TaskGroup;
use App\Wedding\Mapper\TaskGroupDetailsDtoMapper;
use App\Wedding\Mapper\TaskGroupListItemDtoMapper;
use App\Wedding\Mapper\TaskGroupUpdateRequestMapper;
use App\Wedding\Repository\TaskGroupRepository;

class TaskGroupService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly TaskFetchService $taskFetchService,
      private readonly TaskGroupFetchService $taskGroupFetchService,
      private readonly TaskGroupRepository $taskGroupRepository) {}

  /**
   * @return array<TaskGroupListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (TaskGroup $taskGroup) => TaskGroupListItemDtoMapper::map($taskGroup),
            $this->taskGroupRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?TaskGroupListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?TaskGroupDetailsDto {
    return TaskGroupDetailsDtoMapper::map(
        $this->taskGroupRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?TaskGroupUpdateRequest {
    return TaskGroupUpdateRequestMapper::map(
        $this->taskGroupRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      TaskGroupCreateRequest $taskGroupCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);
    $tasks =
        array_map(fn (int $taskId) => $this->taskFetchService->fetchTask($taskId, $userId),
            $taskGroupCreateRequest->getTasks());

    $taskGroup = (new TaskGroup())
        ->setName($taskGroupCreateRequest->getName())
        ->setWedding($wedding);

    array_walk($tasks, fn (Task $task) => $taskGroup->addTask($task));

    $this->taskGroupRepository->save($taskGroup);
  }

  public function update(
      int $id,
      TaskGroupUpdateRequest $taskGroupUpdateRequest,
      int $userId): void {
    $taskGroup = $this->taskGroupFetchService->fetchTaskGroup($id, $userId);
    $addedTasks =
        array_map(fn (int $taskId) => $this->taskFetchService->fetchTask($taskId, $userId),
            array_filter(
                $taskGroupUpdateRequest->getTasks(),
                fn (int $taskId) => !in_array(
                    $taskId,
                    array_map(fn (Task $task) => $task->getId(),
                        $taskGroup->getTasks()->toArray()),
                    true)));
    $removedTasks =
        array_filter(
            $taskGroup->getTasks()->toArray(),
            fn (Task $task) => !in_array(
                $task->getId(),
                $taskGroupUpdateRequest->getTasks(),
                true));

    $taskGroup->setName($taskGroupUpdateRequest->getName());

    array_walk($addedTasks, fn (Task $task) => $taskGroup->addTask($task));
    array_walk($removedTasks, fn (Task $task) => $taskGroup->removeTask($task));

    $this->taskGroupRepository->save($taskGroup);
  }

  public function delete(int $id, int $userId): void {
    $this->taskGroupRepository->softDeleteById(
        $this->taskGroupFetchService->fetchTaskGroup($id, $userId)->getId());
  }
}