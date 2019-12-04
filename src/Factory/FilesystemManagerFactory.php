<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Filesystem\Factory;

use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Filesystem\FilesystemConfig;
use Ixocreate\Filesystem\FilesystemInterface;
use Ixocreate\Filesystem\FilesystemManager;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;

final class FilesystemManagerFactory implements SubManagerFactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return SubManagerInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        /** @var FilesystemConfig $filesystemConfig */
        $filesystemConfig = $container->get(FilesystemConfig::class);
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        foreach ($filesystemConfig->names() as $serviceName) {
            $serviceManagerConfigurator->addService($serviceName, FilesystemFactory::class);
        }

        return new FilesystemManager(
            $container,
            $serviceManagerConfigurator->getServiceManagerConfig(),
            FilesystemInterface::class
        );
    }
}
