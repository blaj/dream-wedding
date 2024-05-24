<?php

namespace App\Localization\EventSubscriber;

use App\Localization\Enum\Localization;
use App\Localization\Event\ChangeLanguageEvent;
use App\Security\Dto\UserData;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class ChangeLanguageEventSubscriber implements EventSubscriberInterface {

  public function __construct(
      private readonly Security $security,
      private readonly UserRepository $userRepository,
      private readonly RouterInterface $router) {}

  public static function getSubscribedEvents(): array {
    return [
        ChangeLanguageEvent::class => 'onChangeLanguageEvent',
        LoginSuccessEvent::class => 'onLoginSuccessEvent'];
  }

  public function onChangeLanguageEvent(ChangeLanguageEvent $changeLanguageEvent): void {
    $entityUser = $this->getEntityUser();

    if ($entityUser === null) {
      return;
    }

    $entityUser->setLanguage($changeLanguageEvent->localization);
    $this->userRepository->save($entityUser);
  }

  public function onLoginSuccessEvent(LoginSuccessEvent $loginSuccessEvent): void {
    $entityUser = $this->getEntityUser();

    if ($entityUser === null) {
      return;
    }

    $request = $loginSuccessEvent->getRequest();

    if (Localization::tryFrom($request->getLocale()) === $entityUser->getLanguage()) {
      return;
    }

    $loginSuccessEvent->setResponse(
        new RedirectResponse(
            $this->router->generate(
                'home_index',
                ['_locale' => $entityUser->getLanguage()->value])));
  }

  private function getEntityUser(): ?User {
    $user = $this->security->getUser();

    if (!$user instanceof UserData) {
      return null;
    }

    return $this->userRepository->findOneById($user->getUserId());
  }
}