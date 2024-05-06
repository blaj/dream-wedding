<?php

namespace App\User\Service;

use App\User\Dto\UserRegisterRequest;
use App\User\Dto\UserSettingsRequest;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {

  public function __construct(
      private readonly UserFetchService $userFetchService,
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

  public function updateSettings(UserSettingsRequest $userSettingsRequest, int $userId): void {
    $user = $this->userFetchService->fetchUser($userId);

    $user->setPassword(
        $this->userPasswordHasher->hashPassword($user, $userSettingsRequest->getNewPassword()));

    $this->userRepository->save($user);
  }
}