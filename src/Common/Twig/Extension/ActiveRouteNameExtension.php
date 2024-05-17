<?php

namespace App\Common\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ActiveRouteNameExtension extends AbstractExtension {

  public function getFilters(): array {
    return [
        new TwigFilter('activeRouteName', [$this, 'activeRouteName']),
        new TwigFilter('activeRouteNames', [$this, 'activeRouteNames'])
    ];
  }

  public function activeRouteName(
      string $actualRouteName,
      string $routeName,
      string $class): string {
    return strtoupper($actualRouteName) === strtoupper($routeName) ? $class : '';
  }

  /**
   * @param array<string> $routeNames
   */
  public function activeRouteNames(
      string $actualRouteName,
      array $routeNames,
      string $class): string {
    return in_array(
        strtoupper($actualRouteName),
        array_map(fn (string $routeName) => strtoupper($routeName), $routeNames),
        true) ? $class : '';
  }
}