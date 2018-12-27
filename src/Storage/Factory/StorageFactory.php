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

use Ixocreate\Contract\ServiceManager\FactoryInterface;
use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Filesystem\Adapter\FilesystemAdapterSubManager;
use Ixocreate\Filesystem\Storage\StorageConfig;
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
