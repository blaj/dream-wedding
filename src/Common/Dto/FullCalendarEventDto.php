<?php

namespace App\Common\Dto;

use App\Common\Utils\DateTimeImmutableUtils;
use DateTimeImmutable;
use JsonSerializable;

readonly class FullCalendarEventDto implements JsonSerializable {

  public function __construct(
      public int $id,
      public ?DateTimeImmutable $start,
      public ?DateTimeImmutable $end,
      public string $url,
      public string $title,
      public ?string $description) {}

  /**
   * @return array<string, null|int|string>
   */
  public function jsonSerialize(): array {
    return [
        'id' => $this->id,
        'start' => $this->start?->format(DateTimeImmutableUtils::$dateTimeFormat),
        'end' => $this->end?->format(DateTimeImmutableUtils::$dateTimeFormat),
        'url' => $this->url,
        'title' => $this->title,
        'description' => $this->description
    ];
  }
}