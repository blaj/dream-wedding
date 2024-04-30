<?php

namespace App\Wedding\Service;

use App\Wedding\Dto\TableCreateRequest;
use App\Wedding\Dto\TableDetailsDto;
use App\Wedding\Dto\TableListItemDto;
use App\Wedding\Dto\TableUpdateRequest;
use App\Wedding\Entity\Table;
use App\Wedding\Mapper\TableDetailsDtoMapper;
use App\Wedding\Mapper\TableListItemDtoMapper;
use App\Wedding\Mapper\TableUpdateRequestMapper;
use App\Wedding\Repository\TableRepository;

class TableService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly TableFetchService $tableFetchService,
      private readonly TableRepository $tableRepository) {}

  /**
   * @return array<TableListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(fn (Table $table) => TableListItemDtoMapper::map($table),
            $this->tableRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?TableListItemDto $dto) => $dto !== null);
  }

  public function getOne(int $id, int $userId): ?TableDetailsDto {
    return TableDetailsDtoMapper::map($this->tableRepository->findOneByIdAndUserId($id, $userId));
  }

  public function getUpdateRequest(int $id, int $userId): ?TableUpdateRequest {
    return TableUpdateRequestMapper::map(
        $this->tableRepository->findOneByIdAndUserId($id, $userId));
  }

  public function create(
      int $weddingId,
      TableCreateRequest $tableCreateRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);

    $table = (new Table())
        ->setName($tableCreateRequest->getName())
        ->setDescription($tableCreateRequest->getDescription())
        ->setType($tableCreateRequest->getType())
        ->setNumberOfSeats($tableCreateRequest->getNumberOfSeats())
        ->setWedding($wedding);

    $this->tableRepository->save($table);
  }

  public function update(int $id, TableUpdateRequest $tableUpdateRequest, int $userId): void {
    $table = $this->tableFetchService->fetchTable($id, $userId);

    $table
        ->setName($tableUpdateRequest->getName())
        ->setDescription($tableUpdateRequest->getDescription())
        ->setType($tableUpdateRequest->getType())
        ->setNumberOfSeats($tableUpdateRequest->getNumberOfSeats());

    $this->tableRepository->save($table);
  }

  public function delete(int $id, int $userId): void {
    $this->tableRepository->softDeleteById(
        $this->tableFetchService->fetchTable($id, $userId)->getId());
  }
}