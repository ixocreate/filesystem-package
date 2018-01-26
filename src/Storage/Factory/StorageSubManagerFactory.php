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

use KiwiSuite\Filesystem\Storage\StorageConfig;
use KiwiSuite\Filesystem\Storage\StorageSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

final class StorageSubManagerFactory implements SubManagerFactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return SubManagerInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        /** @var StorageConfig $storageConfig */
        $storageConfig = $container->get(StorageConfig::class);
        foreach ($storageConfig->getStorageNames() as $storage) {
            $serviceManagerConfigurator->addFactory($storage, FilesystemFactory::class);
            $serviceManagerConfigurator->addLazyService($storage, Filesystem::class);
        }

        return new StorageSubManager(
            $container,
            $serviceManagerConfigurator->getServiceManagerConfig(),
            FilesystemInterface::class
        );
    }
}
