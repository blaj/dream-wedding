<?php

namespace App\User\Service;

use App\User\Dto\UserRegisterRequest;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {

  public function __construct(
      private readonly UserRepository $userRepository,
      private readonly UserPasswordHasherInterface $userPasswordHasher) {}

  public function register(UserRegisterRequest $userRegisterRequest): void {
    $user = (new User())
        ->setUsername($userRegisterRequest->getUsername())
        ->setEmail($userRegisterRequest->getEmail());

    $user->setPassword(
        $this->userPasswordHasher->hashPassword($user, $userRegisterRequest->getPassword()));

    $this->userRepository->save($user);
  }
}