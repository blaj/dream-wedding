<?php

namespace App\Common\Dto;

use DateTimeImmutable;

readonly class FullCalendarQueryDto {

  public function __construct(public DateTimeImmutable $start, public DateTimeImmutable $end) {}
}