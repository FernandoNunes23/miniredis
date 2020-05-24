<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Phpfastcache\Drivers\Files\Driver;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions(array(
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Driver::class => function (ContainerInterface $c) {
            // Setup File Path on your config files
            // Please note that as of the V6.1 the "path" config
            // can also be used for Unix sockets (Redis, Memcache, etc)
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '/var/www/phpfastcache.com/dev/tmp', // or in windows "C:/tmp/"
            ]));

            // In your class, function, you can call the Cache
            $instanceCache = CacheManager::getInstance('files');

            return $instanceCache;
        }
    ));
};
