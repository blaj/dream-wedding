<?php

namespace App\User\Entity\Enum;

enum Role: string {

  case USER = 'USER';
  case MODERATOR = 'MODERATOR';
  case ADMIN = 'ADMIN';
}
