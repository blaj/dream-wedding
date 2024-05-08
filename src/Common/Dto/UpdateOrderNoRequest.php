<?php

namespace App\Common\Dto;

readonly class UpdateOrderNoRequest {

  public function __construct(public int $orderNo) {}
}