<?php

namespace App\Wedding\Dto;

class TaskUpdateCompletedRequest {

  private bool $completed;

  public function isCompleted(): bool {
    return $this->completed;
  }

  public function setCompleted(bool $completed): self {
    $this->completed = $completed;

    return $this;
  }
}