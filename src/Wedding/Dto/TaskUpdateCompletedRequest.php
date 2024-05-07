<?php

namespace App\Wedding\Dto;

readonly class TaskUpdateCompletedRequest {

  public function __construct(public bool $completed) {}
}