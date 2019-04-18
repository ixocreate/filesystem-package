<?php
declare(strict_types=1);
namespace Ixocreate\Filesystem\Package;

use Ixocreate\Filesystem\Package\Adapter\Factory\FilesystemAdapterSubManagerFactory;
use Ixocreate\Filesystem\Package\Adapter\FilesystemAdapterSubManager;
use Ixocreate\Filesystem\Package\Storage\Factory\StorageConfigFactory;
use Ixocreate\Filesystem\Package\Storage\Factory\StorageSubManagerFactory;
use Ixocreate\Filesystem\Package\Storage\StorageConfig;
use Ixocreate\Filesystem\Package\Storage\StorageSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(StorageConfig::class, StorageConfigFactory::class);
$serviceManager->addSubManager(FilesystemAdapterSubManager::class, FilesystemAdapterSubManagerFactory::class);
$serviceManager->addSubManager(StorageSubManager::class, StorageSubManagerFactory::class);
