<?php

namespace App\Common\Pagination\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class AllowedSortByField extends Constraint {

  /**
   * @param array<string> $fields
   */
  public function __construct(
      public readonly array $fields,
      mixed $options = null,
      ?array $groups = null,
      mixed $payload = null) {
    parent::__construct($options, $groups, $payload);
  }

  public function getMessage(): string {
    return 'sort-field-not-allowed';
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }
}