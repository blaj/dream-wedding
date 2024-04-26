<?php

declare(strict_types = 1);

use App\Common\Config\EmailConfig;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function(ContainerConfigurator $containerConfigurator): void {
  $services = $containerConfigurator->services();

  $services->defaults()
      ->autowire()
      ->autoconfigure();

  $services->load('App\\', __DIR__ . '/../src/')
      ->exclude([
          __DIR__ . '/../src/DependencyInjection/',
          __DIR__ . '/../src/Entity/',
          __DIR__ . '/../src/Kernel.php',
      ]);

  $services
      ->set(EmailConfig::class)
      ->arg('$fromAddress', '%env(resolve:EMAIL_FROM_ADDRESS)%')
      ->arg('$fromName', '%env(resolve:EMAIL_FROM_NAME)%');
};
