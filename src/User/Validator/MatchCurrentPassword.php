<?php

namespace App\User\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class MatchCurrentPassword extends Constraint {

  public function getMessage(): string {
    return 'not-match-current-password';
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }
}