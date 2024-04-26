<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\TwigConfig;

return static function(
    ContainerConfigurator $containerConfigurator,
    TwigConfig $twigConfig): void {
  $containerConfigurator->extension('tbbc_money', [
      'currencies' => [
          'PLN',
      ],
      'reference_currency' => 'PLN',
      'decimals' => 2,
  ]);

  $twigConfig->formThemes(['@TbbcMoney/Form/fields.html.twig']);
};
