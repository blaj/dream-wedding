<?php

namespace App\Wedding\Entity\Enum;

enum RoleType: string {

  case OWNER = 'OWNER';
  case WRITE = 'WRITE';
  case READ = 'READ';
}