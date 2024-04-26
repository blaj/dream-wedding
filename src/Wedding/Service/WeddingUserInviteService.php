<?php

namespace App\Wedding\Service;

use App\Common\Config\EmailConfig;
use App\User\Entity\User;
use App\User\Service\UserFetchService;
use App\Wedding\Dto\WeddingUserInviteListItemDto;
use App\Wedding\Dto\WeddingUserInviteRequest;
use App\Wedding\Entity\Enum\RoleType;
use App\Wedding\Entity\WeddingUser;
use App\Wedding\Entity\WeddingUserInvite;
use App\Wedding\Mapper\WeddingUserInviteListItemDtoMapper;
use App\Wedding\Repository\WeddingUserInviteRepository;
use App\Wedding\Repository\WeddingUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class WeddingUserInviteService {

  public function __construct(
      private readonly WeddingFetchService $weddingFetchService,
      private readonly WeddingUserInviteFetchService $weddingUserInviteFetchService,
      private readonly UserFetchService $userFetchService,
      private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
      private readonly MailerInterface $mailer,
      private readonly EmailConfig $emailConfig,
      private readonly EntityManagerInterface $entityManager,
      private readonly WeddingUserInviteRepository $weddingUserInviteRepository,
      private readonly WeddingUserRepository $weddingUserRepository) {}

  /**
   * @return array<WeddingUserInviteListItemDto>
   */
  public function getList(int $weddingId, int $userId): array {
    return array_filter(
        array_map(
            fn (WeddingUserInvite $weddingUserInvite) => WeddingUserInviteListItemDtoMapper::map(
                $weddingUserInvite),
            $this->weddingUserInviteRepository->findAllByWeddingIdAndUserId($weddingId, $userId)),
        fn (?WeddingUserInviteListItemDto $dto) => $dto !== null);
  }

  public function delete(int $id, int $userId): void {
    $this->weddingUserInviteRepository->softDeleteById(
        $this->weddingUserInviteFetchService->fetchWeddingUserInvite($id, $userId)->getId());
  }

  public function acceptInvite(string $token, int $userId): void {
    $user = $this->userFetchService->fetchUser($userId);
    $weddingUserInvite =
        $this->weddingUserInviteRepository->findOneByUserEmailAndToken($user->getEmail(), $token)
        ??
        throw new EntityNotFoundException('Wedding user not found');

    $weddingUser =
        (new WeddingUser())
            ->setRole($weddingUserInvite->getRole())
            ->setWedding($weddingUserInvite->getWedding())
            ->setUser($this->userFetchService->fetchUser($userId));

    $this->entityManager->beginTransaction();
    $this->weddingUserRepository->save($weddingUser, false);
    $this->weddingUserInviteRepository->softDeleteById($weddingUserInvite->getId());
    $this->entityManager->flush();
    $this->entityManager->commit();
  }

  public function invite(
      int $weddingId,
      WeddingUserInviteRequest $weddingUserInviteRequest,
      int $userId): void {
    $wedding = $this->weddingFetchService->fetchWedding($weddingId, $userId);
    $token = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash(random_bytes(10));
    $user = $this->userFetchService->fetchUser($userId);

    $weddingUserInvite =
        (new WeddingUserInvite())
            ->setWedding($wedding)
            ->setToken($token)
            ->setUserEmail($weddingUserInviteRequest->getEmail())
            ->setRole($weddingUserInviteRequest->getRole());

    $this->weddingUserInviteRepository->save($weddingUserInvite);

    $this->sendInviteMail(
        $weddingUserInviteRequest->getEmail(),
        $token,
        $user->getEmail(),
        $weddingUserInviteRequest->getRole());
  }

  public function resendInviteEmail(int $id, int $userId): void {
    $weddingUserInvite = $this->weddingUserInviteFetchService->fetchWeddingUserInvite($id, $userId);

    $token = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash(random_bytes(10));
    $user = $this->userFetchService->fetchUser($userId);

    $weddingUserInvite->setToken($token);
    $this->weddingUserInviteRepository->save($weddingUserInvite);

    $this->sendInviteMail(
        $weddingUserInvite->getUserEmail(),
        $token,
        $user->getEmail(),
        $weddingUserInvite->getRole());
  }

  private function sendInviteMail(
      string $email,
      string $token,
      string $fromEmail,
      RoleType $role): void {
    $email = (new TemplatedEmail())
        ->from(new Address($this->emailConfig->fromAddress, $this->emailConfig->fromName))
        ->to(new Address($email))
        ->subject('Time for Symfony Mailer!')
        ->htmlTemplate('email/invite.html.twig')
        ->context(
            [
                'token' => $token,
                'fromEmail' => $fromEmail,
                'role' => $role]);

    try {
      $this->mailer->send($email);
    } catch (TransportExceptionInterface $e) {

    }
  }
}