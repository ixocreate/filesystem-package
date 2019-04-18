<?php
declare(strict_types=1);
namespace Ixocreate\Package\Filesystem;

use Ixocreate\Package\Filesystem\Adapter\Factory\FilesystemAdapterSubManagerFactory;
use Ixocreate\Package\Filesystem\Adapter\FilesystemAdapterSubManager;
use Ixocreate\Package\Filesystem\Storage\Factory\StorageConfigFactory;
use Ixocreate\Package\Filesystem\Storage\Factory\StorageSubManagerFactory;
use Ixocreate\Package\Filesystem\Storage\StorageConfig;
use Ixocreate\Package\Filesystem\Storage\StorageSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(StorageConfig::class, StorageConfigFactory::class);
$serviceManager->addSubManager(FilesystemAdapterSubManager::class, FilesystemAdapterSubManagerFactory::class);
$serviceManager->addSubManager(StorageSubManager::class, StorageSubManagerFactory::class);
