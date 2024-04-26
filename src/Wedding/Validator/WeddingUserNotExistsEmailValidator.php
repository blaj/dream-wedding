<?php

namespace App\Wedding\Validator;

use App\Wedding\Repository\WeddingUserInviteRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class WeddingUserNotExistsEmailValidator extends ConstraintValidator {

  public function __construct(
      private readonly WeddingUserInviteRepository $weddingUserInviteRepository) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof WeddingUserNotExistsEmail) {
      throw new UnexpectedTypeException($constraint, WeddingUserNotExistsEmail::class);
    }

    if (!is_string($value)) {
      throw new UnexpectedValueException($value, 'string');
    }

    if (strlen($value) === 0) {
      return;
    }

    if (!$this->weddingUserInviteRepository->existsByUserEmail($value)) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}