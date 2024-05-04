<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\TaskGroup;
use App\Wedding\Repository\TaskGroupRepository;
use Doctrine\ORM\EntityNotFoundException;

class TaskGroupFetchService {

  public function __construct(private readonly TaskGroupRepository $taskGroupRepository) {}

  public function fetchTaskGroup(int $id, int $userId): TaskGroup {
    return $this->taskGroupRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Task group not found');
  }
}