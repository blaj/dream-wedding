<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\SmsMessage;

return static function(ContainerConfigurator $containerConfigurator): void {
  $containerConfigurator->extension('framework', [
      'messenger' => [
          'failure_transport' => 'failed',
          'transports' => [
              'async' => [
                  'dsn' => '%env(MESSENGER_TRANSPORT_DSN)%',
                  'options' => [
                      'use_notify' => true,
                      'check_delayed_interval' => 60000,
                  ],
                  'retry_strategy' => [
                      'max_retries' => 3,
                      'multiplier' => 2,
                  ],
              ],
              'failed' => 'doctrine://default?auto_setup=0&table_name=messenger.messages&queue_name=failed',
          ],
          'default_bus' => 'messenger.bus.default',
          'buses' => [
              'messenger.bus.default' => [],
          ],
          'routing' => [
              SendEmailMessage::class => 'async',
              ChatMessage::class => 'async',
              SmsMessage::class => 'async',
          ],
      ],
  ]);
};
