<?php

namespace App\Common\Twig\Extension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GetRouteAttributeExtension extends AbstractExtension {

  public function __construct(private readonly RequestStack $requestStack) {}

  public function getFunctions(): array {
    return [
        new TwigFunction('getRouteAttribute', [$this, 'getRouteAttribute'])
    ];
  }

  public function getRouteAttribute(string $attributeName): mixed {
    $request = $this->requestStack->getCurrentRequest();

    if ($request === null) {
      return null;
    }

    return $request->attributes->has($attributeName)
        ? $request->attributes->get($attributeName)
        : null;
  }
}