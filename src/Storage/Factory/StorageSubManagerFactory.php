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
namespace Ixocreate\Filesystem\Storage\Factory;

use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerInterface;
use Ixocreate\Filesystem\Storage\StorageConfig;
use Ixocreate\Filesystem\Storage\StorageSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfig;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;
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
            $serviceManagerConfigurator->addFactory($storage, StorageFactory::class);
            $serviceManagerConfigurator->addLazyService($storage, Filesystem::class);
        }

        return new StorageSubManager(
            $container,
            new ServiceManagerConfig($serviceManagerConfigurator),
            FilesystemInterface::class
        );
    }
}
