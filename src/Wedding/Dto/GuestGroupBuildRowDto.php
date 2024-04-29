<?php

namespace App\Wedding\Dto;

class GuestGroupBuildRowDto {

  private GuestGroupListItemDto $guestGroupListItemDto;

  /**
   * @var array<GuestListItemDto>
   */
  private array $guestsListItemDto;

  public function getGuestGroupListItemDto(): GuestGroupListItemDto {
    return $this->guestGroupListItemDto;
  }

  public function setGuestGroupListItemDto(
      GuestGroupListItemDto $guestGroupListItemDto): self {
    $this->guestGroupListItemDto = $guestGroupListItemDto;

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