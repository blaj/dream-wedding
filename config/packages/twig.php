<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\TwigConfig;

return static function(TwigConfig $twigConfig, ContainerConfigurator $containerConfigurator): void {
  $twigConfig
      ->fileNamePattern('*.twig')
      ->formThemes(['bootstrap_5_layout.html.twig']);

  if ($containerConfigurator->env() === 'test') {
    $twigConfig->strictVariables(true);
  }
};
