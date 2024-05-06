<?php

namespace App\User\Validator;

use App\User\Dto\UserSettingsRequest;
use App\User\Repository\UserRepository;
use Error;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MatchCurrentPasswordValidator extends ConstraintValidator {

  public function __construct(
      private readonly UserRepository $userRepository,
      private readonly UserPasswordHasherInterface $userPasswordHasher) {}

  public function validate(mixed $value, Constraint $constraint): void {
    if (!$constraint instanceof MatchCurrentPassword) {
      throw new UnexpectedTypeException($constraint, MatchCurrentPassword::class);
    }

    if (!$value instanceof UserSettingsRequest) {
      throw new UnexpectedValueException($value, UserSettingsRequest::class);
    }

    $user = $this->userRepository->findOneById($value->getUserId());

    if ($user === null) {
      return;
    }

    try {
      if (strlen($value->getNewPassword()) === 0) {
        return;
      }

      if ($this->userPasswordHasher->isPasswordValid($user, $value->getCurrentPassword())) {
        return;
      }
    } catch (Error $error) {
      return;
    }

    $this->context
        ->buildViolation($constraint->getMessage())
        ->addViolation();
  }
}