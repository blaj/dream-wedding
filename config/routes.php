<?php

declare(strict_types = 1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function(RoutingConfigurator $routingConfigurator): void {
  $routingConfigurator->import([
      'path' => '../src/User/Controller/',
      'namespace' => 'App\User\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Home/Controller/',
      'namespace' => 'App\Home\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Dashboard/Controller/',
      'namespace' => 'App\Dashboard\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Wedding/Controller/',
      'namespace' => 'App\Wedding\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Security/Controller/',
      'namespace' => 'App\Security\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Meta/Controller/',
      'namespace' => 'App\Meta\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Localization/Controller/',
      'namespace' => 'App\Localization\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Offer/Controller/',
      'namespace' => 'App\Offer\Controller',
  ], 'attribute');
};
