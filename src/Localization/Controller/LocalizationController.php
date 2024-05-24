<?php

namespace App\Localization\Controller;

use App\Common\Utils\UrlUtils;
use App\Localization\Enum\Localization;
use App\Localization\Event\ChangeLanguageEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Symfony\Component\Routing\RouterInterface;

#[Route(path: '/localization', name: 'localization_')]
class LocalizationController extends AbstractController {

  public function __construct(
      private readonly EventDispatcherInterface $eventDispatcher,
      private readonly RouterInterface $router) {}

  #[Route(
      path: '/change-locale/{locale}',
      name: 'change_locale',
      requirements: ['locale' => new EnumRequirement(Localization::class)],
      methods: 'GET')]
  public function changeLocale(Localization $locale, Request $request): Response {
    $this->eventDispatcher->dispatch(new ChangeLanguageEvent($locale));

    return $this->redirect(
        $this->router->generate(
            UrlUtils::getLastRoute($request, $this->router),
            [
                ...UrlUtils::getLastParameters($request, $this->router),
                '_locale' => $locale->value]));
  }
}