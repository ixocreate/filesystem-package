<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Adapter\Factory;

use Ixocreate\Application\Service\ServiceManagerConfig;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Filesystem\Adapter\FilesystemAdapterSubManager;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;
use League\Flysystem\AdapterInterface;

final class FilesystemAdapterSubManagerFactory implements SubManagerFactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SubManagerInterface
     */
    public function __invoke(
        ServiceManagerInterface $container,
        $requestedName,
        array $options = null
    ): SubManagerInterface {
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
