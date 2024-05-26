<?php

namespace App\Common\Pagination\Dto;

enum Order: string {
  case ASC = 'ASC';
  case DESC = 'DESC';
}