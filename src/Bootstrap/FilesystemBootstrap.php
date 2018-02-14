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
namespace KiwiSuite\Filesystem\Bootstrap;

use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\ConfiguratorItem\ServiceManagerConfiguratorItem;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\Filesystem\Adapter\Factory\FilesystemAdapterSubManagerFactory;
use KiwiSuite\Filesystem\Adapter\FilesystemAdapterSubManager;
use KiwiSuite\Filesystem\Storage\Factory\StorageConfigFactory;
use KiwiSuite\Filesystem\Storage\Factory\StorageSubManagerFactory;
use KiwiSuite\Filesystem\Storage\StorageConfig;
use KiwiSuite\Filesystem\Storage\StorageSubManager;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class FilesystemBootstrap implements BootstrapInterface
{
    public function configure(ConfiguratorRegistry $configuratorRegistry): void
    {
        /** @var ServiceManagerConfigurator $serviceManagerConfigurator */
        $serviceManagerConfigurator = $configuratorRegistry->get(ServiceManagerConfiguratorItem::class);

        $serviceManagerConfigurator->addFactory(StorageConfig::class, StorageConfigFactory::class);
        $serviceManagerConfigurator->addSubManager(StorageSubManager::class, StorageSubManagerFactory::class);
        $serviceManagerConfigurator->addSubManager(FilesystemAdapterSubManager::class, FilesystemAdapterSubManagerFactory::class);
    }

    public function addServices(ServiceRegistry $serviceRegistry): void
    {
    }

    public function getConfiguratorItems(): ?array
    {
        return null;
    }

    public function getDefaultConfig(): ?array
    {
        return [
            'storage' => [],
        ];
    }

    public function boot(ServiceManager $serviceManager): void
    {
    }
}
