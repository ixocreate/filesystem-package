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
namespace KiwiSuite\Filesystem\Adapter\Factory;

use KiwiSuite\Filesystem\Adapter\FilesystemAdapterSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerInterface;
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
        $serviceManagerConfig = new ServiceManagerConfig(
            [
                'local' => LocalFactory::class,
                'ftp' => FtpFactory::class,
                'ftpd' => FtpdFactory::class,
                'null' => NullFactory::class,
            ],
            [],
            [],
            [],
            [],
            []
        );
        return new FilesystemAdapterSubManager(
            $container,
            $serviceManagerConfig,
            AdapterInterface::class
        );
    }
}
