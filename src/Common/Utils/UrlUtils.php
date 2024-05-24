<?php

namespace App\Common\Utils;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class UrlUtils {

  private static string $httpReferer = 'HTTP_REFERER';

  private static string $parametersRouteKey = '_route';
  private static string $parametersControllerKey = '_controller';
  private static string $parametersLocaleKey = '_locale';

  public static function getLastRoute(Request $request, RouterInterface $router): string {
    $parameters = $router->match(self::getReferer($request));

    return array_key_exists(self::$parametersRouteKey, $parameters)
        ? $parameters[self::$parametersRouteKey]
        : throw new BadRequestException(); // TODO: throw exception in utils is bad idea
  }

  /**
   * @return array<string, mixed>
   */
  public static function getLastParameters(Request $request, RouterInterface $router): array {
    $lastParameters = $router->match(Request::create(self::getReferer($request))->getPathInfo());

    unset($lastParameters[self::$parametersRouteKey]);
    unset($lastParameters[self::$parametersControllerKey]);
    unset($lastParameters[self::$parametersLocaleKey]);

    return $lastParameters;
  }

  private static function getReferer(Request $request): string {
    $referer = $request->server->getString(self::$httpReferer);
    $baseUrl = $request->getSchemeAndHttpHost();
    $offset = is_int(strpos($referer, $baseUrl)) ? strpos($referer, $baseUrl) : 0;

    return str_replace($baseUrl, '', substr($referer, $offset));
  }
}