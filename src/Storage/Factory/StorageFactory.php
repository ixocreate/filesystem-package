<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Package\Storage\Factory;

use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Filesystem\Package\Adapter\FilesystemAdapterSubManager;
use Ixocreate\Filesystem\Package\Storage\StorageConfig;
use League\Flysystem\Filesystem;

final class StorageFactory implements FactoryInterface
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
        /** @var StorageConfig $storageConfig */
        $storageConfig = $container->get(StorageConfig::class);

        $params = $storageConfig->getStorageParams($requestedName);

        $adapter = $container->get(FilesystemAdapterSubManager::class)->build($params['type'], $params['options']);

        return new Filesystem($adapter);
    }
}
