<?php

declare(strict_types = 1);

use App\Common\Doctrine\Filter\SoftDeleteFilter;
use App\Common\Doctrine\Function\CastFunction;
use App\Common\Doctrine\Function\RandomFunction;
use App\Common\Doctrine\Function\ToCharFunction;
use App\Common\Doctrine\Type\BigIntType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Tbbc\MoneyBundle\Type\MoneyType;

return static function(ContainerConfigurator $containerConfigurator): void {
  $containerConfigurator->extension('doctrine', [
      'dbal' => [
          'connections' => [
              'default' => [
                  'url' => '%env(resolve:DATABASE_URL)%',
                  'profiling_collect_backtrace' => '%kernel.debug%',
                  'driver' => 'pdo_pgsql',
                  'server_version' => '16',
                  'charset' => 'UTF8',
                  'use_savepoints' => true,
              ],
              'migrations' => [
                  'url' => '%env(resolve:DATABASE_MIGRATIONS_URL)%',
                  'profiling_collect_backtrace' => '%kernel.debug%',
                  'driver' => 'pdo_pgsql',
                  'server_version' => '16',
                  'charset' => 'UTF8',
                  'use_savepoints' => true,
              ],
          ],
          'types' => [
              'bigint' => BigIntType::class,
              'money' => MoneyType::class
          ],
      ],
      'orm' => [
          'auto_generate_proxy_classes' => true,
          'enable_lazy_ghost_objects' => true,
          'report_fields_where_declared' => true,
          'validate_xml_mapping' => true,
          'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
          'auto_mapping' => true,
          'filters' => [
              'soft_delete' => [
                  'class' => SoftDeleteFilter::class,
                  'enabled' => true
              ]
          ],
          'mappings' => [
              'Common' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Common/Entity',
                  'prefix' => 'App\Common\Entity',
                  'alias' => 'Common',
              ],
              'Wedding' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Wedding/Entity',
                  'prefix' => 'App\Wedding\Entity',
                  'alias' => 'Common',
              ],
              'User' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/User/Entity',
                  'prefix' => 'App\User\Entity',
                  'alias' => 'User',
              ],
              'Post' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Post/Entity',
                  'prefix' => 'App\Post\Entity',
                  'alias' => 'Post',
              ],
              'Offer' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/Offer/Entity',
                  'prefix' => 'App\Offer\Entity',
                  'alias' => 'Offer',
              ],
              'FileStorage' => [
                  'type' => 'attribute',
                  'is_bundle' => false,
                  'dir' => '%kernel.project_dir%/src/FileStorage/Entity',
                  'prefix' => 'App\FileStorage\Entity',
                  'alias' => 'FileStorage',
              ],
          ],
          'controller_resolver' => [
              'auto_mapping' => false,
          ],
          'dql' => [
              'string_functions' => [
                  'CAST' => CastFunction::class,
                  'TO_CHAR' => ToCharFunction::class
              ],
              'numeric_functions' => [
                  'RANDOM' => RandomFunction::class
              ]
          ]
      ],
  ]);
  if ($containerConfigurator->env() === 'test') {
    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'dbname_suffix' => '_test%env(default::TEST_TOKEN)%',
        ],
    ]);
  }
  if ($containerConfigurator->env() === 'prod') {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'auto_generate_proxy_classes' => false,
            'proxy_dir' => '%kernel.build_dir%/doctrine/orm/Proxies',
            'query_cache_driver' => [
                'type' => 'pool',
                'pool' => 'doctrine.system_cache_pool',
            ],
            'result_cache_driver' => [
                'type' => 'pool',
                'pool' => 'doctrine.result_cache_pool',
            ],
        ],
    ]);
    $containerConfigurator->extension('framework', [
        'cache' => [
            'pools' => [
                'doctrine.result_cache_pool' => [
                    'adapter' => 'cache.app',
                ],
                'doctrine.system_cache_pool' => [
                    'adapter' => 'cache.system',
                ],
            ],
        ],
    ]);
  }
};
