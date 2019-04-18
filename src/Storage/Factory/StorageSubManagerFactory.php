<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Storage\Factory;

use Ixocreate\Application\Service\ServiceManagerConfig;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Filesystem\Storage\StorageConfig;
use Ixocreate\Filesystem\Storage\StorageSubManager;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

final class StorageSubManagerFactory implements SubManagerFactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @return SubManagerInterface
     */
    public function __invoke(
        ServiceManagerInterface $container,
        $requestedName,
        array $options = null
    ): SubManagerInterface {
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
