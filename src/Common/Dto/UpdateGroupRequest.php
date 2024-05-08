<?php

namespace App\Common\Dto;

readonly class UpdateGroupRequest {

  public function __construct(public ?int $groupId) {}
}