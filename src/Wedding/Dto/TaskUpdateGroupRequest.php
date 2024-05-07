<?php

namespace App\Wedding\Dto;

readonly class TaskUpdateGroupRequest {

  public function __construct(public ?int $groupId) {}
}