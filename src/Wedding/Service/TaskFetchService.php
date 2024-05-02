<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\Task;
use App\Wedding\Repository\TaskRepository;
use Doctrine\ORM\EntityNotFoundException;

class TaskFetchService {

  public function __construct(private readonly TaskRepository $taskRepository) {}

  public function fetchTask(int $id, int $userId): Task {
    return $this->taskRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Task not found');
  }
}