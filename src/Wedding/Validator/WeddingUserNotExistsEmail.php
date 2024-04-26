<?php

namespace App\Wedding\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class WeddingUserNotExistsEmail extends Constraint {

  public function getMessage(): string {
    return 'wedding-user-email-is-exists';
  }

  public function getTargets(): string {
    return self::PROPERTY_CONSTRAINT;
  }
}