<?php

namespace App\Wedding\Dto;

readonly class TaskUpdateOrderNoRequest {

  public function __construct(public int $orderNo) {}
}