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
namespace Ixocreate\Filesystem\Adapter\Factory;

use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerInterface;
use Ixocreate\Filesystem\Adapter\FilesystemAdapterSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfig;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;
use League\Flysystem\AdapterInterface;

final class FilesystemAdapterSubManagerFactory implements SubManagerFactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SubManagerInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        $serviceManagerConfigigurator = new ServiceManagerConfigurator();
        $serviceManagerConfigigurator->addFactory('local', LocalFactory::class);
        $serviceManagerConfigigurator->addFactory('ftp', FtpFactory::class);
        $serviceManagerConfigigurator->addFactory('ftpd', FtpdFactory::class);
        $serviceManagerConfigigurator->addFactory('null', NullFactory::class);

        return new FilesystemAdapterSubManager(
            $container,
            new ServiceManagerConfig($serviceManagerConfigigurator),
            AdapterInterface::class
        );
    }
}
