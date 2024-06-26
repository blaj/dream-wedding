<?php

namespace App\Common\Twig\Extension;

use App\Common\Twig\Dto\ActualRoute;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetActualRouteNameExtension extends AbstractExtension {

  public function __construct(private readonly RequestStack $requestStack) {}

  public function getFunctions(): array {
    return [
        new TwigFunction('getActualRoute', [$this, 'getActualRoute'])
    ];
  }

  public function getActualRoute(): ?ActualRoute {
    $request = $this->requestStack->getCurrentRequest();

    if ($request === null) {
      return null;
    }

    $route = $request->attributes->getString('_route', '');

    return new ActualRoute($route, $request->attributes);
  }
}