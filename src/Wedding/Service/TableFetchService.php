<?php

namespace App\Wedding\Service;

use App\Wedding\Entity\Table;
use App\Wedding\Repository\TableRepository;
use Doctrine\ORM\EntityNotFoundException;

class TableFetchService {

  public function __construct(private readonly TableRepository $tableRepository) {}

  public function fetchTable(int $id, int $userId): Table {
    return $this->tableRepository->findOneByIdAndUserId($id, $userId)
        ??
        throw new EntityNotFoundException('Table not found');
  }
}