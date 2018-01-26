<?php
/**
 * kiwi-suite/filesystem (https://github.com/kiwi-suite/filesystem)
 *
 * @package kiwi-suite/filesystem
 * @see https://github.com/kiwi-suite/filesystem
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Filesystem\Storage\Factory;

use KiwiSuite\Config\Config;
use KiwiSuite\Filesystem\Storage\StorageConfig;
use KiwiSuite\ServiceManager\FactoryInterface;
use KiwiSuite\ServiceManager\ServiceManagerInterface;

final class StorageConfigFactory implements FactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $storageConfig = [];
        if ($container->has(Config::class)) {
            /** @var Config $config */
            $config = $container->get(Config::class);
            $storageConfig = $config->get("storage", []);
        }
        return new StorageConfig($storageConfig);
    }
}
