<?php

namespace App\Common\Twig\Extension;

use App\Common\Twig\Dto\ActualRoute;
use Symfony\Component\HttpFoundation\ParameterBag;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ActiveRouteNameExtension extends AbstractExtension {

  public function getFilters(): array {
    return [
        new TwigFilter('activeRouteName', [$this, 'activeRouteName']),
        new TwigFilter('activeRouteNames', [$this, 'activeRouteNames']),
        new TwigFilter('activeRouteWithSubRoutesName', [$this, 'activeRouteWithSubRoutesName']),
        new TwigFilter('isActiveRouteNames', [$this, 'isActiveRouteNames'])
    ];
  }

  public function activeRouteName(
      ?ActualRoute $actualRoute,
      string $routeName,
      string $class,
      ?string $parameterKey = null,
      mixed $parameterValue = null): string {
    if ($actualRoute === null) {
      return '';
    }

    return strtoupper($actualRoute->name) === strtoupper($routeName)
    && self::isActualParameterValid($actualRoute->parameters, $parameterKey, $parameterValue)
        ? $class
        : '';
  }

  /**
   * @param array<string> $routeNames
   */
  public function activeRouteNames(
      ?ActualRoute $actualRoute,
      array $routeNames,
      string $class): string {
    if ($actualRoute === null) {
      return '';
    }

    return in_array(
        strtoupper($actualRoute->name),
        array_map(fn (string $routeName) => strtoupper($routeName), $routeNames),
        true) ? $class : '';
  }

  public function activeRouteWithSubRoutesName(
      ?ActualRoute $actualRoute,
      string $routeName,
      string $class): string {
    if ($actualRoute === null) {
      return '';
    }

    return str_starts_with($actualRoute->name, $routeName) ? $class : '';
  }

  /**
   * @param array<string> $routeNames
   */
  public function isActiveRouteNames(
      ?ActualRoute $actualRoute,
      array $routeNames,
      ?string $parameterKey = null,
      mixed $parameterValue = null): bool {
    if ($actualRoute === null) {
      return false;
    }

    return in_array(
            strtoupper($actualRoute->name),
            array_map(fn (string $routeName) => strtoupper($routeName), $routeNames),
            true)
        && self::isActualParameterValid($actualRoute->parameters, $parameterKey, $parameterValue);
  }

  private static function isActualParameterValid(
      ParameterBag $parameters,
      string $parameterKey = null,
      mixed $parameterValue = null): bool {
    if ($parameterKey === null || $parameterValue === null) {
      return true;
    }

    return $parameters->has($parameterKey) && $parameters->get($parameterKey) == $parameterValue;
  }
}