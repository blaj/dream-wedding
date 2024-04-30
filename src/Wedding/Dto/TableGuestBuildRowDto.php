<?php

namespace App\Wedding\Dto;

class TableGuestBuildRowDto {

  private TableListItemDto $tableListItemDto;

  /**
   * @var array<GuestListItemDto>
   */
  private array $guestsListItemDto = [];

  public function getTableListItemDto(): TableListItemDto {
    return $this->tableListItemDto;
  }

  public function setTableListItemDto(TableListItemDto $tableListItemDto): self {
    $this->tableListItemDto = $tableListItemDto;

    return $this;
  }

  /**
   * @return array<GuestListItemDto>
   */
  public function getGuestsListItemDto(): array {
    return $this->guestsListItemDto;
  }

  /**
   * @param array<GuestListItemDto> $guestsListItemDto
   */
  public function setGuestsListItemDto(array $guestsListItemDto): self {
    $this->guestsListItemDto = $guestsListItemDto;

    return $this;
  }

  public function addGuestListItemDto(GuestListItemDto $guestListItemDto): self {
    $this->guestsListItemDto[] = $guestListItemDto;

    return $this;
  }
}